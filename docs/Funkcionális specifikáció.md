# Személyes Pénzügyi Napló – Funkcionális Specifikáció

## 1. Bevezetés

Ez a dokumentum a **Személyes Pénzügyi Napló** webalkalmazás funkcionális specifikációját tartalmazza. A rendszer lehetővé teszi, hogy a felhasználók rögzítsék napi kiadásaikat, a rendszer pedig automatikusan kategorizálja azokat mesterséges intelligencia segítségével.

---

## 2. Rendszeráttekintés

A rendszer fő funkciói:

* Felhasználói autentikáció (regisztráció, bejelentkezés, kijelentkezés)
* Kiadások rögzítése
* Automatikus kategorizálás AI segítségével
* Kategóriák manuális szerkesztése
* Lista- és statisztikanézet

### 2.1 Fő komponensek

* **Frontend:** HTML/CSS/JS alapú felület
* **Backend:** PHP alapú API (auth.php, db.php, transactions.php)
* **Adatbázis:** MySQL
* **AI modul:** Kategorizáló endpoint (OpenAI API vagy lokális modell)

### 2.2 ÁBRA HELYE – Rendszerarchitektúra

*(Ide kerül majd a rendszerarchitektúra ábra)*

---

## 3. Felhasználói Szerepkörök

### 3.1 Regisztrált felhasználó

Jogosultságok:

* Saját tranzakciók rögzítése
* Tranzakciók szerkesztése, törlése
* Kategóriák szerkesztése
* Statisztikák megtekintése

Admin szerepkör nem szükséges ebben a verzióban.

---

## 4. Funkcionális Követelmények

### 4.1 Bejelentkezés és regisztráció

* A felhasználó e-mail + jelszó kombinációval regisztrál.
* Az e-mail cím egyedi.
* A jelszó minimum 8 karakter.
* Sikeres regisztráció után automatikus bejelentkeztetés.
* Bejelentkezett állapotban több oldalhoz is csak autentikált felhasználó fér hozzá.

### 4.2 Tranzakciók kezelése

#### 4.2.1 Új tranzakció rögzítése

* A felhasználó megadja: `összeg`, `leírás`, `dátum`.
* A `category` mező kezdésben üres.
* Mentés után a backend elküldi az AI modulnak a leírást.
* Az AI visszaad egy kategória javaslatot.
* A kategória automatikusan kitöltésre kerül.
* A felhasználó később módosíthatja.

### 4.3 Tranzakciók listázása

* A felhasználó a saját tranzakcióit látja időrendi sorrendben.
* Szűrés dátumra és kategóriára.
* Rendezés: dátum, összeg.

### 4.4 Statisztikák

* Diagram havi kiadásokról kategóriánként.
* Top 5 legnagyobb kiadás.
* Összkiadás adott hónapra.

### ÁBRA HELYE – Statisztikai diagramok

*(Ide kerül majd a havi statisztikák grafikus ábrája)*

---

## 5. Nem Funkcionális Követelmények

* **Biztonság:** Jelszavak hash-elve legyenek (bcrypt).
* **Teljesítmény:** Tranzakciók listázása max. 1 mp alatt.
* **Skálázhatóság:** AI modul cserélhető legyen.
* **Reszponzív felület:** Mobilon és asztali gépen is működik.

---

## 6. Adatbázis Séma

### 6.1 Users tábla

| oszlop        | típus        | leírás                      |
| ------------- | ------------ | --------------------------- |
| user_id       | INT PK       | Egyedi felhasználóazonosító |
| email         | VARCHAR(255) | Felhasználó e-mail címe     |
| password_hash | VARCHAR(255) | Hash-elt jelszó             |

### 6.2 Transactions tábla

| oszlop      | típus         | leírás                              |
| ----------- | ------------- | ----------------------------------- |
| tx_id       | INT PK        | Tranzakció azonosító                |
| user_id     | INT FK        | Felhasználóhoz kötve                |
| description | VARCHAR(255)  | Tranzakció leírása                  |
| amount      | DECIMAL(10,2) | Kiadás összege                      |
| date        | DATE          | Dátum                               |
| category    | VARCHAR(100)  | AI által javasolt/kinyert kategória |

### ÁBRA HELYE – ER Diagram

*(Ide kerül majd az adatbázis ER diagramja)*

---

## 7. API Végpontok

### 7.1 POST /auth/register

* Input: email, password
* Output: token

### 7.2 POST /auth/login

* Input: email, password
* Output: token

### 7.3 POST /transactions/add

* Input: amount, description, date
* Output: új tranzakció adatai kategóriával

### 7.4 GET /transactions/list

* Input: opcionális szűrők
* Output: tranzakciók listája

### ÁBRA HELYE – API Flow Diagram

*(Ide kerül majd az API hívások folyamata)*

---

## 8. AI Integráció

* Leírás alapján kategóriát javasol.
* Példa input: "MOL tankolás 25 000 Ft"
* Példa output: "Üzemanyag"

---

## 9. Felhasználói Felület

### 9.1 Főoldal

* Gyors összegzés az adott hónapról.
* Diagram.

### 9.2 Tranzakciólista oldal

* Lapozható lista.
* Szűrés és rendezés.

### 9.3 Új tranzakció oldal

* Űrlap.
* Mentés után automatikus AI kategorizálás.

### ÁBRA HELYE – UI drótvázak

*(Ide jönnek a wireframe-ek)*

---

## 10. Jövőbeli bővítések

* Mobilalkalmazás
* Több pénznem támogatása
* Közös pénzügyi háztartás kezelése

---

*Dokumentum verzió: 1.0*
