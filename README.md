# Greek-Dynamic-Calendar
Greek Dynamic Calendar - Ελληνικό Δυναμικό Ημερολόγιο

# Εορτολόγιο με αναζήτηση ονόματος

 * Η εφαρμογή φορτώνει γιορτές και αργίες από DataBase
 * Δυναμική βάση δεδομένων

# Δυναμικό εορτολόγιο με PHP και MySQL

Το project περιλαμβάνει:
- `index.php`: το βασικό frontend με μήνα, γιορτές, αναζήτηση και αργίες
- `db.php`: σύνδεση PDO με MySQL
- `functions.php`: βοηθητικές συναρτήσεις ημερολογίου και queries
- `schema.sql`: δημιουργία βάσης, πινάκων και αρχικών δεδομένων
- `config.php`: ρυθμίσεις βάσης

![Greek Dynamic Calendar](images/images1.jpg) 

## Εγκατάσταση

1. Δημιούργησε βάση MySQL ή άφησε το `schema.sql` να τη δημιουργήσει.
2. Κάνε import το `schema.sql`.
3. Άλλαξε τα στοιχεία στο `config.php`.
4. Ανέβασε τα αρχεία σε server με PHP 8+.
5. Άνοιξε το `index.php`.

## Προτεινόμενες επεκτάσεις

- Ξεχωριστή σελίδα `name.php?q=` για SEO-friendly αποτελέσματα
- Υποστήριξη κινητών εορτών με υπολογισμό ανά έτος
- Pagination ή A-Z index για μεγάλα datasets

### Copyright

HauHet plc. © 2023-2026. All Rights Reserved. [HauHet plc.](https://hauhet.co/)
