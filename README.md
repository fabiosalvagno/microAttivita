# microAttivita

Piccola web app in **Laravel 12** per gestire "micro-attività" di italiano L2, pensate soprattutto per studenti giapponesi ma riutilizzabili da chiunque insegni italiano.

L'idea è offrire attività molto brevi, da fare sullo **smartphone** in pochi minuti, come momento finale di lezione o come compito leggero, senza dover usare piattaforme pesanti.

> ⚠️ Questo progetto è pensato come base didattica / prototipo.  
> Usalo, copialo, modificalo come vuoi (licenza MIT), ma verifica tu stesso sicurezza e configurazione prima di usarlo in produzione.

---

## ✨ Funzionalità

- Micro-attività a **scelta multipla** (domanda → 3 opzioni → feedback).
- Modalità **normale** o **a tempo** (es. 60 secondi, feedback finale).
- Focus su:
  - lessico
  - grammatica
  - comprensione di testi / notizie
- Visualizzazione di **semplici statistiche**:
  - quanti studenti hanno risposto
  - quante risposte corrette
- Supporto per:
  - attività con **audio** (es. ascolto di una notizia con domande)
  - (in sperimentazione) **upload di brevi file audio** da parte degli studenti, per attività di produzione orale.

---

## 🧑‍🏫 Esempi di attività (online)

Alcune micro-attività usate in classe:

- Brano audio sulla notizia della *Lettera di Colombo* ritrovata a Dallas dai Carabinieri  
  👉 https://atti.fabiosalvagno.com/svolgi/attivita_NrD53L3c27eiEZL8  

- **Al bar** e **Chiedere la strada** (domande di ripasso, a tempo: 60 s)  
  👉 https://atti.fabiosalvagno.com/svolgi/attivita_wIpiUGO2BWLld1co  

- Scelta dell’articolo determinativo giusto (a tempo: 60 s)  
  👉 https://atti.fabiosalvagno.com/svolgi/attivita_Ka1bVG5ZwgJPamgz  

---

## 🛠️ Requisiti

- PHP 8.2+ (consigliato)
- Composer
- MySQL/MariaDB (o altro DB supportato da Laravel)
- Node.js + npm (solo se vuoi ricompilare gli asset front-end)

---

## 🚀 Installazione (sviluppo locale)

```bash
git clone https://github.com/fabiosalvagno/microAttivita.git
cd microAttivita

cp .env.example .env
composer install

# configura il database nel file .env:
# DB_DATABASE, DB_USERNAME, DB_PASSWORD, APP_URL, ecc.

php artisan key:generate
php artisan migrate

php artisan serve
```

Poi apri nel browser:

```text
http://localhost:8000
```

---

## 🧩 Configurazione audio (opzionale)

Per alcune attività è previsto un file audio (ad esempio generato con Python e `edge_tts`).  
Nel codice puoi:

- indicare il percorso del file audio nell’attività
- far comparire un semplice player audio nella pagina dello studente

L’implementazione concreta può variare, ma l’idea è che la struttura dell’attività resti la stessa:  
testo + domanda + 3 opzioni → lo studente ascolta e risponde.

---

## 🔊 Upload audio da parte degli studenti (sperimentale)

È presente anche una funzione (in sviluppo) che permette agli studenti di caricare **brevi file audio** come risposta ad alcune attività.

Questa parte è pensata per:

- esercizi di produzione orale
- pratica di pronuncia

> ⚠️ Se usi questa funzione in produzione, controlla bene:
> - limite dimensione file
> - tipi di file accettati
> - validazione e sicurezza lato server
> - eventuale storage esterno (S3, ecc.)

---

## 💡 Utilizzo didattico

La web app è usata in:

- classi universitarie di 15–20 studenti (18–20 anni)
- ultimi 10 minuti di lezione, come momento di:
  - assestamento del contenuto
  - ripasso rapido
  - verifica "leggera"

L’obiettivo è costruire una serie di strumenti **piccoli ma concreti** per integrare attività digitali nelle lezioni di lingua senza dover usare LMS pesanti.

---

## 🤝 Contributi e fork

Il progetto è aperto e pensato per essere copiato, riadattato e modificato.

- Puoi fare **fork** liberamente.
- Puoi usare parti del codice nei tuoi progetti.
- Se vuoi proporre miglioramenti, puoi aprire una **Issue** o una **Pull Request**.

---

## 📄 Licenza

Questo progetto è distribuito con licenza **MIT**.  
Vedi il file [`LICENSE`](./LICENSE) per i dettagli.

---

## 🌍 Short description (English)

**microAttivita** is a small Laravel 12 web app for managing “micro-activities” for Italian as a foreign language, mainly used with Japanese university students.  
Activities are short, mobile-friendly multiple-choice tasks (with optional audio and simple stats), designed for the last few minutes of class or as light homework.  

Feel free to fork, reuse and adapt under the MIT license.
