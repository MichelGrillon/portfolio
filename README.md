# portfolio
 gestionnaire d'image en mvc

Mise en place les bases d'une application MVC pour la gestion d'images (CRUD).
Mise en place des mesures de sécurité dans cette application :
Dans le détail :

Correction xss, Correction CSRF, Correction contre le Hijacking (détournement de session), Correction contre l'injection SQL via des requêtes préparées (avec "prepare()" et "execute()", Pour les images (faille d'upload), Include() et mots de passe en clair...

Les fichiers de connections à la base de données (/Core/Dbconnect et connect) sont anonymisés.
Des liens ont été modifiés (et sont donc incorrects).
