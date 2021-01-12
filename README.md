# Sujet

Site E-commerce films.
Nous sommes engagés par un distributeur/producteur de films, il stock actuellement ses films sur une plateforme de streaming en ligne qui permet déjà le visionnage des films par la transmission d'un lien.
Il nous demande de créer une interface pour la gestion et la vente en ligne.
Il y aura donc :
- Page qui liste tous les films selon leurs catégories et les préférences pour les utilisateurs + une page de recherche (alphabétique, catégorie, plus vus, meilleurs notes)
- Page pour chaque film,
- Page de paiement,
- Confirmation du paiement par email envoyée au client.
- Gestion des films par les administrateurs (Ajout, suppression, modification).
- Génération d’une facture en PDF que le client peut télécharger et retrouver à tout moment dans son espace client
- Un email est envoyé au client 1 jour après avoir regardé le film pour l’inviter à laisser une note sur le film
- Laisser une note aux films (uniquement ceux qu’on a acheté) de 1 à 5 étoiles + un commentaire (les commentaires apparaitront sur la fiche film, et la note permettra un classement)
- La plateforme doit limiter l’achat des films aux personnes situées en France (question de droits)
- Lorsqu’on paye, on reçoit un lien qui permet de regarder le film. Ce lien expire après 7 jours

# Installation

Suivre les commandes suivantes pour installer le site sur votre ordinateur

1. `cd /C/Documents/Sites`
2. `git clone https://github.com/victorweiss/wf3-614.git`
3. `cd wf3-614`
4. `composer install`
5. Créer le fichier `.env.local` en changeant les variables
6. `symfony serve -d`

Une fois le projet installé, pour récupérer les mises à jours il suffira d'être dans le dossier /wf3-614 et faire 
- `git pull`
- `composer install`

