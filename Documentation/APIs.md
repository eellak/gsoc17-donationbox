# Ανάλυση και σχεδίαση των APIs.

Στο παρόν έγγραφο γίνεται η ανάλυση και η σχεδίαση των APIs που θα πρέπει να υπάρχουν στο σύστημα.

Το εκσυγχρονισμένο donation boxe δε θα είναι ένα απλό κουτί δωρεάς, αλλά θα βασίζεται σε ένα ολόκληρο σύστημα που θα παρέχεται στα donation boxes και θα αποτελείται από ένα *δίκτυο* των συνδεδεμένων donation boxes όπου θα αλληλεπιδρούν με ένα κεντρικό κόμβο ανταλλάσσοντας πληροφορίες.
Για αυτό το λόγο καταλαβαίνουμε πως η επικοινωνία των donation boxes είναι ένα ζήτημα ζωτικής σημασίας.

Η επικοινωνία των donation boxes θα γίνεται με τον εξής τρόπο :

![connection diagram](https://i.imgur.com/jQWxutU.png)

Η επικοινωνία των donation boxes θα επιτυγχάνεται με τον κεντρικό κόμβο μέσω
[APIs](https://en.wikipedia.org/wiki/Application_programming_interface)
τα οποία θα είναι υπεύθυνα για την αποστολή και την λήψη δεδομένων.


## API **αποστολής** δεδομένων για κάθε έργο δωρεάς.

Θα παρέχετε η δυνατότητα αποστολής των δεδομένων του κάθε έργου δωρεάς **και** μέσω ενός REST API από την πλευρά της κεντρικής ιστοσελίδας ( η οποία βασίζεται στο WordPress). Το εν λόγο REST API, θα παρέχει τα δεδομένα σε μορφή [JSON](https://en.wikipedia.org/wiki/JSON).

##### Γιατί επιλέγουμε η κεντρική ιστοσελίδα για την παροχή των δεδομένων των έργων δωρεάς μέσω REST API;
Πιστεύουμε πως ο **ευκολότερος** και **ασφαλέστερος** τρόπος είναι να παρέχουμε τα δεδομένα αυτά μέσω της κεντρικής ιστοσελίδας. <br>
* **Ευκολότερος** τρόπος, γιατί μέσω της κεντρικής ιστοσελίδας ο κάθε χρήστης θα μπορεί να ενημερώνετε για όλα τα έργα δωρεάς και την τρέχουσα πορεία τους είτε από μια κανονική σελίδα παρουσίασης (WordPress post), είτε αλλάζοντας λιγάκι το URL μέσω του REST API που θα παρέχουμε. Έτσι για τον χρήστη αλλά και για μελλοντικές εφαρμογές που θα θέλουν τα δεδομένα από τα έργα δωρεάς, πιστεύουμε θα είναι πολύ πιο εύκολο να παραμετροποιήσουν απλώς λιγάκι το URL της κεντρική ιστοσελίδας παρά να συνδέονται αλλού.

* **Ασφαλέστερος** τρόπος γιατί το το WordPress στις τελευταίες εκδώσεις του έχει υλοποιήσει και ενσωματώσει ένα REST API το οποίο μπορούμε να το επεκτείνουμε και να το φέρουμε στα μέτρα μας. Θεωρούμε λοιπόν πολύ πιο φρόνιμο και ασφαλέστερο να χρησιμοποιήσουμε το REST API που παρέχει το *ίδιο* το WordPress μαζί με την ασφάλεια που το WordPress μεριμνά για αυτό, παρά να σχεδιάσουμε και να υλοποιήσουμε ένα REST API εξολοκλήρου από την αρχή.

#### Τι πληροφορίες θα παρέχει το REST API.

Οι παρακάτω πληροφορίες που θα πρέπει να παρέχει το REST API, απορρέουν και είναι προσανατολισμένες αρχικά από τις απαιτήσεις των δεδομένων που θα χρειάζονται τα donation boxes.

Μέσω του REST API θα πρέπει να παρέχονται τα ακόλουθα δεδομένα :

* Τίτλος του έργου δωρεάς.
* Περιεχόμενο του έργου δωρεάς.
* Τρέχον χρηματικό ποσό που έχει μαζευτεί για το έργο δωρεάς.
* Χρηματικό ποσό στόχος για το έργο δωρεάς.
* Οργανισμός στον οποίο ανήκει το έργο δωρεάς.
* Κατάσταση του έργου δωρεάς.
* Ημερομηνία τελευταία τροποποίησης του έργου δωρεάς.

Η γενική πρόσβαση στο REST API των δημοσιεύσεων των έργων δωρεάς θα γίνεται από τον σύνδεσμο :

`http://donation_web_site.org/wp-json/wp/v2/donationboxes`

## API για την **λήψη** δεδομένων για κάθε έργο δωρεάς.

Ο καθένας λόγο του REST API που θα παρέχεται από την κεντρική ιστοσελίδα - κόμβο, θα έχει τη δυνατότητα λήψης δεδομένων για τα έργα δωρεάς.
Συγκεκριμένα εμάς μας ενδιαφέρει να υλοποιήσουμε τον τρόπο λήψης των δεδομένων αυτών στα *donation boxes* ώστε να τα εμφανίζουν στην οθόνη του τελικού χρήστη ο οποίος θα επιλέγει σε ποιο έργο δωρεάς θα κάνει δωρεά.

Παρακάτω αναλύουμε και σχεδιάζουμε το API όπου θα είναι υπεύθυνο να ζητάει και να λαμβάνει συγκεκριμένες πληροφορίες των έργων δωρεάς στα donation boxes.

Το API λοιπόν από την πλευρά των donation boxes θα πρέπει να είναι σε θέση να ζητάει :
* Έργα δωρεάς με συγκεκριμένη κατάσταση ( *Ενεργά* ).
* Έργα δωρεάς με ημερομηνίας δημιουργίας ή τελευταία τροποποίησης έπειτα από την τελευταία ενημέρωση που έχει κάνει*.

*Αν π.χ. η τελευταία ενημέρωση που έχει κάνει το donation box, είναι στης 2017-06-09 , 18:49:14 ( ή ώρα ίσος είναι υπερβολή και δε μας ενδιαφέρει ), τότε να ζητάει :

`http://donation_web_site.org/wp-json/wp/v2/donationboxes/2017-06-09`

και να παίρνει όλα τα έργα δωρεάς τα οποία έχουν προστεθεί ή ενημερωθεί από αυτή ( και αυτή ) την ημερομηνία και έπειτα.