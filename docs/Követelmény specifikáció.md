# Követelmény Specifikáció

## 1. Bevezetés

Ez a dokumentum a rendszer **követelmény specifikációját** tartalmazza. A cél, hogy egyértelműen meghatározza a rendszer elvárt működését, funkcionális és nem-funkcionális követelményeit, valamint a fontos fogalmakat és érintetteket. 

---

## 2. Rendszer áttekintése

A fejlesztendő rendszer egy olyan alkalmazás, amely lehetővé teszi, hogy a felhasználó **személyes pénzügyi naplót** vezessen, tranzakciókat rögzítsen, elemezzen, és mesterséges intelligencia segítsége mellett automatikusan kategorizálja a költéseket.

---

## 3. Fogalmak és érintettek

* **Felhasználó:** Bármely személy, aki regisztrál és belép a rendszerbe.
* **Admin:** Jogosultsággal rendelkező felhasználó, aki felhasználókat és rendszerszintű beállításokat kezel.
* **Tranzakció:** A felhasználó által felvitt kiadási vagy bevételi tétel.
* **AI Modul:** A rendszer része, amely automatikusan javasol kategóriát a tranzakciókhoz.

---

## 4. Funkcionális követelmények

### 4.1 Felhasználói követelmények

* **FK1:** A rendszernek biztosítania kell a felhasználók regisztrációját.
* **FK2:** A rendszernek lehetővé kell tennie a biztonságos bejelentkezést.
* **FK3:** A felhasználó képes legyen tranzakciókat rögzíteni (összeg, leírás, dátum).
* **FK4:** A rendszer opcionálisan automatikusan kategorizálja a tranzakciót az AI modul segítségével.
* **FK5:** A felhasználó módosíthatja vagy felülírhatja az AI által javasolt kategóriát.
* **FK6:** A felhasználó képes legyen tranzakciókat listázni, szűrni és törölni.

### 4.2 Admin funkciók

* **FK7:** Az admin képes legyen felhasználók listázására és jogosultságok kezelésére.
* **FK8:** Az admin megtekintheti a rendszer statisztikáit.

---

## 5. Nem-funkcionális követelmények

* **NFK1 (Biztonság):** A jelszavakat titkosítva kell tárolni.
* **NFK2 (Teljesítmény):** A rendszernek átlagosan 1 másodpercen belül kell válaszokat adnia a tranzakciók listázására.
* **NFK3 (Használhatóság):** A felület legyen letisztult, és mobilon is használható.
* **NFK4 (Skálázhatóság):** A rendszernek képesnek kell lennie több ezer tranzakció kezelésére felhasználónként.

---

## 6. Rendszerhatárok és kontextus

### 6.1 Kontextusdiagram (egyszerűsített)

```
+-----------------+         +------------------------+
|     Felhasználó | <-----> |  Webes UI / Frontend   |
+-----------------+         +-----------+------------+
                                        |
                                        v
                             +----------+-----------+
                             |       Backend        |
                             +----------+-----------+
                                        |
                                        v
                             +----------+-----------+
                             |     AI Kategorizáló  |
                             +-----------------------+
```

---

## 7. Rendszerkövetelmények

* **RK1:** A rendszer PHP alapú backenddel készüljön.
* **RK2:** Az adatbázis MySQL legyen.
* **RK3:** Az AI modul REST API-n keresztül érhető el.

---

## 8. Kockázatok

* **K1:** Az AI modul pontatlan kategorizálása félrevezető eredményekhez vezethet.
* **K2:** Nem megfelelő autentikáció esetén adatvédelmi problémák merülhetnek fel.

---

## 9. Mellékletek

### 9.1 Adatmodell vázlat

```
+---------------------+
|       Users         |
+---------------------+
| user_id (PK)        |
| username            |
| password_hash       |
| created_at          |
+---------------------+

+---------------------+
|    Transactions     |
+---------------------+
| tx_id (PK)          |
| user_id (FK)        |
| description         |
| amount              |
| date                |
| category            |
+---------------------+
```

---

*Dokumentum verzió: 1.0*
