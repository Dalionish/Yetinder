<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Yeti;
use DateTime;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class DatabaseService
{
    public function __construct(private Connection $connection)
    {
    }

    public function yetiInsert(Yeti $yeti): void
    {
        $data = [
            'yetiName' => $yeti->getName(),
            'sex' => $yeti->getSex(),
            'age' => $yeti->getAge(),
            'height' => $yeti->getHeight(),
            'weight' => $yeti->getWeight(),
            'coordinates' => $yeti->getLatitude() . ' ' . $yeti->getLongitude(),
            'dateAdded' => $yeti->getAdded(),
            'imageName' => $yeti->getPhotoFilename(),
            'address' => $yeti->getAddress(),
            'userID' => $yeti->getUserID(),
        ];
        try {
            $this->connection->insert('yeti', $data);
        } catch (Exception $e) {
            echo "error: " . $e->getMessage();
        }
    }

    // 10 nejlepe hodnocenych yeti
    public function selectBestYeti(): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('yetiName', 'sex', 'age', 'height', 'weight', 'coordinates', 'imageName', 'address', 'rating')
            ->from('yeti')
            ->orderBy('rating', 'DESC')
            ->setMaxResults(10);
        $results = null;
        try {
            $results = $queryBuilder->fetchAllAssociative();
        } catch (Exception $e) {
            echo "error: " . $e->getMessage();
        }
        $length = count($results);
        for ($i = 0; $i < $length; $i++) {
            if ($results[$i]['sex'] === 'male') {
                $results[$i]['sex'] = 'Muž';
            } else {
                $results[$i]['sex'] = 'Žena';
            }
        }
        return $results;
    }

    public function getUserID(string $userEmail): int
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('userID')
            ->from('user')
            ->where('email = ?')
            ->setParameter(0, $userEmail);
        $userID = null;
        try {
            $userID = $queryBuilder->fetchOne();
        } catch (Exception $e) {
            echo "error: " . $e->getMessage();
        }
        return $userID;
    }

    // Ulozeni uzivatele
    public function userInsert(User $user): void
    {
        $user = [
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'role' => implode('', $user->getRoles())
        ];
        try {
            $this->connection->insert('user', $user);
        } catch (Exception $e) {
            echo "error: " . $e->getMessage();
        }
    }

    // Vyber yeti, ktere uzivatel uz hodnotil
    public function ratedYeti(int $userID): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('yetiID')
            ->from('rating')
            ->where('userID = ?')
            ->setParameter(0, $userID);
        $results = null;
        try {
            $results = $queryBuilder->fetchAllAssociative();
        } catch (Exception $e) {
            echo "error: " . $e->getMessage();
        }

        $ratedYeti = [];
        foreach ($results as $result) {
            $ratedYeti[] = $result['yetiID'];
        }
        return $ratedYeti;
    }

    // Vyber vsech uzivatelem neohodnocenych yeti
    public function yetinderSelect(int $userID): array
    {
        $ratedYeti = $this->ratedYeti($userID);
        if ($ratedYeti == NULL) {
            $ratedYeti[0] = 0;
        }
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('yetiID', 'yetiName', 'sex', 'age', 'height', 'weight', 'coordinates', 'imageName', 'address')
            ->from('yeti', 't')
            ->where($queryBuilder->expr()->notIn('t.yetiID', $ratedYeti));
        $results = null;
        try {
            $results = $queryBuilder->fetchAllAssociative();
        } catch (Exception $e) {
            echo "error: " . $e->getMessage();
        }
        return $results;
    }

    // Vyber nahodneho yeti pro neprihlaseneho uzivatele
    public function yetinderSelectRandom(): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('yetiName', 'sex', 'age', 'height', 'weight', 'coordinates', 'imageName', 'address', 'yetiID')
            ->from('yeti')
            ->orderBy('RAND()')
            ->setMaxResults(1);
        $result = null;
        try {
            $result = $queryBuilder->fetchAllAssociative();
        } catch (Exception $e) {
            echo "error: " . $e->getMessage();
        }
        return $result;
    }

    // Ulozeni hodnoceni (do tabulky hodnoceni + do tabulky yeti ke konkretnimu yeti)
    public function ratingInsert(int $rating, int $userID, int $yetiID): void
    {
        $DateTime = new DateTime();
        $timestamp = $DateTime->getTimestamp();
        $insert = [
            'rating' => $rating,
            'userID' => $userID,
            'yetiID' => $yetiID,
            'timestamp' => $timestamp,
        ];
        try {
            $this->connection->insert('rating', $insert);
        } catch (Exception $e) {
            echo "error: " . $e->getMessage();
        }

        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('rating')
            ->from('yeti')
            ->where('yetiID = ?')
            ->setParameter(0, $yetiID);
        $result = null;
        try {
            $result = $queryBuilder->fetchOne();
        } catch (Exception $e) {
            echo "error: " . $e->getMessage();
        }

        if ($result == 0) {
            $averageRating = $rating;
        } else {
            $averageRating = ($result + $rating) / 2;
        }

        try {
            $this->connection->update('yeti', ['rating' => $averageRating], ['yetiID' => $yetiID]);
        } catch (Exception $e) {
            echo "error: " . $e->getMessage();
        }
    }

    // Vyber statistiky vsech hodnoceni
    public function statistics(): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('r.rating', 'r.yetiID', 'r.timestamp', 'u.email', 'y.yetiName')
            ->from('rating', 'r')
            ->leftJoin('r', 'user', 'u', 'r.userID = u.userID')
            ->leftJoin('r', 'yeti', 'y', 'r.yetiID = y.yetiID')
            ->orderBy('timestamp', 'DESC');
        $results = null;
        try {
            $results = $queryBuilder->fetchAllAssociative();
        } catch (Exception  $e) {
            echo "error: " . $e->getMessage();
        }

        $length = count($results);
        for ($i = 0; $i < $length; $i++) {
            $DateTime = new DateTime();
            $DateTime->setTimestamp($results[$i]['timestamp']);
            $date = $DateTime->format('d.m.y');
            $time = $DateTime->format('H:i:s');
            $results[$i]['date'] = $date;
            $results[$i]['time'] = $time;
        }
        return $results;
    }
}