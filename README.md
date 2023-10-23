# YETINDER
Aplikace yetinder dle zadání (přiloženo v mailu)
## Základní popis
- Aplikaci jsem tvořil s Symfony frameworku v PhpStormu
- Pro tvorbu frontendu jsem využil boostrapu a použil jsem předpřipravenou base page jak bylo doporučeno v zadání (bootstrap example ["Pricing"](https://getbootstrap.com/docs/5.1/examples/pricing/))
- Do databáze jsem se dotazoval přes vrstvu DBAL s Doctrine QueryBuilder jak bylo doporučeno v zadání. Využil jsem MySql databázi, kterou jsem připojil z docker containeru.
- Pro spuštění jsem využíval integrovaný Symfony server.

## Části aplikace
Má aplikace je rozdělena do 5 funkčních částí - stránek, 
1. **Best Of** - Úvodní stránka kde je zobrazeno 10 nejlépe hodnocených yeti s výpisem základních informací včetně fotky a hvězdičkového zobrazení hodnocení (hvězdičky zobrazují s přesností na čtvrt hvězdičky, po najetí myší se zobrazí konkrétní číselná hodnota hodnocení)
2. **Přidat** - Stránka s formulářem pro přídání nového yetiho včetně fotky a pozice, která se zadá interaktivním kliknutím do mapy. Formulář je ošetřen proti různým možným útokům a je validován aby do něj nešlo zadávat nesmysly. Přidávání je povoleno pouze pro přihlášené uživatele.
3. **Yetinder** - Hlavní funkční část aplikace, kde uživatel hodnotí zobrazené yeti 1-5 hvězdičkami. Yeti se zobrazují podle geografické vzdálenosti (nejprve se zobrazují nejblíže umístění yeti), tato vzdálenost je u zobrazeného yetiho ukázána. Pokud uživatel v prohlížeči nepovolí svoji polohu, yeti jsou mu zobrazeni v náhodném pořadí. Hodnotit mohou pouze přihlášení uživatelé, nepřihlášenému uživateli se vždy zobrazí náhodný yeti. Yetiho může uživatel ohodnotit pouze jednou, poté už se mu v hodnocení nezobrazí.
4. **Statistika** - Jednoduchá statistika hodnocení všech yeti. Lze zvolit období za které se mají hodnocení zobrazovat (24 hodin - 7 dní - 30 dní - 1 rok - všechno). Na stránce se zobrazuje vždy 50 výsledků, na spodní části stránky je navigace pro jednoduché překlikávání mezi jednotlivými stránkami.
5. **Přihlásit** - Tlačítko Přihlásit přesměruje na stránku s dvěma formuláři, jeden pro registraci nového uživatele, druhý pro příhlášení uživatele. Všichni uživatelé mají stejná přístupová práva. Po přihlášení lze toto stejné tlačítko využít i k odhlášení.
