<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class Yeti
{
    private string $name;
    private string $sex;
    private int $age;
    private float $height;
    private float $weight;
    private string $latitude;
    private string $longitude;
    private string $address;
    private int $added;
    private string $photoFilename;
    private int $userID;

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('name', new NotBlank());
        $metadata->addPropertyConstraint('name', new Length([
            'min' => 2,
            'max' => 50,
            'minMessage' => 'Jméno musí obsahovat alespoň 2 znaky',
            'maxMessage' => 'Jméno nesmí obsahovat více než 50 znaků',
        ]));
        $metadata->addPropertyConstraint('sex', new NotBlank());
        $metadata->addPropertyConstraint('age', new NotBlank());
        $metadata->addPropertyConstraint('age', new Range([
            'min' => 1,
            'max' => 99,
            'notInRangeMessage' => 'Věk musí být v rozmezí 1 - 99',
        ]));
        $metadata->addPropertyConstraint('height', new NotBlank());
        $metadata->addPropertyConstraint('height', new Range([
            'min' => 20,
            'max' => 499,
            'notInRangeMessage' => 'Výška musí být v rozmezí 20 - 499',
        ]));
        $metadata->addPropertyConstraint('weight', new NotBlank());
        $metadata->addPropertyConstraint('weight', new Range([
            'min' => 10,
            'max' => 199,
            'notInRangeMessage' => 'Hmotnost musí být v rozmezí 10 - 199',
        ]));
        $metadata->addPropertyConstraint('latitude', new NotBlank(message: 'Zadejte polohu kliknutím do mapy'));
    }

    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getAdded(): int
    {
        return $this->added;
    }

    /**
     * @param int $added
     */
    public function setAdded(int $added): void
    {
        $this->added = $added;
    }

    /**
     * @return string
     */
    public function getLatitude(): string
    {
        return $this->latitude;
    }

    /**
     * @param string $latitude
     */
    public function setLatitude(string $latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return string
     */
    public function getLongitude(): string
    {
        return $this->longitude;
    }

    /**
     * @param string $longitude
     */
    public function setLongitude(string $longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSex(): string
    {
        return $this->sex;
    }

    /**
     * @param string $sex
     */
    public function setSex(string $sex): void
    {
        $this->sex = $sex;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    /**
     * @return float
     */
    public function getHeight(): float
    {
        return $this->height;
    }

    /**
     * @param float $height
     */
    public function setHeight(float $height): void
    {
        $this->height = $height;
    }

    /**
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     */
    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }

    /**
     * @return string
     */
    public function getPhotoFilename(): string
    {
        return $this->photoFilename;
    }

    /**
     * @param string $photoFilename
     */
    public function setPhotoFilename(string $photoFilename): void
    {
        $this->photoFilename = $photoFilename;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return int
     */
    public function getUserID(): int
    {
        return $this->userID;
    }

    /**
     * @param int $userID
     */
    public function setUserID(int $userID): void
    {
        $this->userID = $userID;
    }

}