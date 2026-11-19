## Partie 0

**0.3** Non, `$v1` et `$v2` partagent pas la meme valeur de `$marque`. car chaque objet est une instance differente de la classe Voiture, donc chacun a ses propres attributs en memoire.

## Partie 1

**1.1** On remarque que le script s'arrete avec une Fatal error des qu'on essaye d'acceder a un membre private depuis l'exterieur :
- `echo $p->cin;` -> `Fatal error: Uncaught Error: Cannot access private property Personne::$cin`
- `echo $p->dateNaissance;` -> `Fatal error: Uncaught Error: Cannot access private property Personne::$dateNaissance`
- `echo $p->getAge();` -> `Fatal error: Uncaught Error: Call to private method Personne::getAge() from global scope`

**1.2** C'est dangereux pcq le CIN est une info perso et sensible. Si c'est en public, n'importe quelle partie du code a l'exterieur peut la lire ou la modifier direct (ex: mettre n'importe quoi ou une chaine vide) sans aucune verif.

**1.3** Non elle est pas inutile. Meme si on peut pas l'appeler depuis l'exterieur, une autre methode `public` a l'interieur de la classe Personne peut l'utiliser via `$this->getAge()` pour calculer l'age et le retourner ou l'afficher.

**1.4** Non le comportement change pas depuis l'exterieur. On a tjr une Fatal error (`Cannot access protected property Personne::$cin`), pcq `protected` autorise l'acces seulement depuis la classe elle-meme et ses classes filles, pas depuis l'exterieur.

## Partie 2

**2.1.a**
- Avec `new Voiture("Noire")`, `$couleur` prend `"Noire"` et `$vitesse` garde `0` par defaut.
- Avec `new Voiture(120)`, PHP convertit `120` en chaine `"120"` pour `$couleur` (sauf si `strict_types=1` ou ca met une TypeError) et `$vitesse` reste a `0`. C'est pas logique pcq le 1er parametre est la couleur.

**2.1.b** L'avantage de la promotion de proprietes (PHP 8) c'est que le code est bcp plus court et propre : on declare et initialise les attributs direct dans le `__construct()` sans les repeter 3 fois.

**2.2.a** On a une erreur fatale : `Fatal error: Cannot redeclare Personne::__construct()`. Pcq en PHP on peut pas avoir plusieurs constructeurs (pas de surcharge de methode comme en Java).

**2.2.b** On met un seul constructeur avec des valeurs par defaut (ex: `string $nom = "", string $prenom = ""`), comme ca on peut appeler `new Personne()` sans args ou `new Personne("...", "...")`.

**2.2.c** Une methode statique de fabrique (`named constructor`) c'est une methode `public static` qui cree et retourne une instance de la classe (`return new self(...)`). Ca permet d'avoir plusieurs facons de construire un objet avec des noms clairs (ex: `Personne::depuisTableau($data)`).

**2.3.a** L'ordre est :
1. `Voiture Blanche creee.`
2. `Voiture Blanche detruite.`
3. `Fin du script.`

**2.3.b** Si on enleve `unset($maVoiture)`, `Fin du script.` s affiche avant `Voiture Blanche detruite.`, car l objet est detruit automatiquement seulement a la fin du script.

**2.3.c** Ils sont detruits dans l'ordre inverse de creation (LIFO) : d'abord `$b`, ensuite `$a`.

## Partie 3

**3.1** Les 4 methodes marchent bien :
- `$p->nom = "Hachimi"` -> modifie la propriete private via `__set`
- `echo $p->nom` -> affiche `Hachimi` via `__get`
- `isset($p->nom)` -> retourne `bool(true)` puis `bool(false)` apres `unset($p->nom)`
- `$p->telephone = "0600000000"` -> affiche `Propriete telephone inexistante` au lieu de creer une propriete dynamique.

**3.2** Sans `__set`, ecrire `$p->nom = "X"` provoque une Fatal error (`Cannot access private property Personne::$nom`). Avec `__set`, PHP appelle automatiquement `__set("nom", "X")` ce qui permet d'intercepter l'affectation (et avec notre verif de 3.4 ca lance une exception pcq "X" fait moins de 2 caracteres).

**3.3** Oui, `__set` peut casser l'encapsulation si on laisse modifier toutes les proprietes `private` sans aucun controle. Pour eviter ca, il faut valider les donnees recues dans `__set` (ex: verifier `strlen`, refuser les valeurs vides, ou lancer une `InvalidArgumentException`).

## Partie 4

**4.1** Pcq `__toString()` est une methode de la classe `Personne`, donc elle est a l'interieur de la classe et a le droit d'appeler une methode `private` comme `$this->getAge()`.

**4.2** En mode normal (sans `strict_types=1`), PHP convertit automatiquement l'entier en chaine (ex: `25` devient `"25"`). Par contre si on met `declare(strict_types=1);` en haut du fichier, ca donne une `TypeError` pcq `__toString()` doit retourner un `string`.

**4.3** L'interet de `__toString()` c'est qu'on a pas besoin d'appeler une methode a chaque fois pr afficher l'objet, il se comporte direct comme du texte. Deux situations pratiques :
1. Faire un simple `echo $p;` ou mettre l'objet dans une chaine (`"Etudiant : $p"`).
2. Faire une concatenation directe (`"Resultat : " . $p`) ou afficher rapidement des logs/messages.

## Partie 5

**5.1** Non, le mot de passe (`secret123`) n'apparait pas dans la chaine serialisee, on voit seulement `nom` ("Alice") et `age` (25).

**5.2** Si on commente `__serialize()`, PHP serialise toutes les proprietes par defaut, meme les `private`, donc `"secret123"` apparait en clair dans la chaine. Le risque c'est une fuite de donnees sensibles si cette chaine est stockee dans un cookie, un fichier ou des logs.

**5.3** Apres `unserialize()`, `$motDePasse` vaut `""` (chaine vide). Dans une vraie appli, il faudrait soit redemander le mot de passe (re-authentification), soit recuperer le hash du mdp depuis la BDD au besoin (et tjr hasher les mdp avec `password_hash`).

**5.4** Il faut jamais faire `unserialize()` sur des donnees utilisateur pcq ca ouvre la faille **PHP Object Injection** : un pirate peut fabriquer une fausse chaine serialisee pour instancier d'autres classes du projet et declencher leurs methodes magiques (`__destruct`, `__wakeup`...) afin d'executer du code malveillant. L'alternative sure pour echanger des donnees c'est `json_encode()` et `json_decode()`.

## Partie 6

**6.1.b** Non on peut pas faire `$c->miauler()`, pcq `$c` est un `Chien` et `miauler()` est que dans `Chat`.

**6.2.a** Erreur fatale (`Typed property Personne::$nom must not be accessed before initialization`). car en PHP le constructeur du parent est pas appele tout seul si la classe fille a son propre constructeur, donc `$nom` reste pas initialise.

**6.2.b** `$nom` est en `protected` pour que `Etudiant` (classe fille) puisse y acceder avec `$this->nom`. En `private` ca serait bloque.

**6.3.a**
1. Redefinir `afficher()` : `Fatal error: Cannot override final method Base::afficher()`
2. `SousOutil extends Outil` : `Fatal error: Class SousOutil cannot extend final class Outil`
3. `$p->reference = "REF-002"` : `Fatal error: Uncaught Error: Cannot modify readonly property Produit::$reference`

**6.3.b** `readonly` : un ID ou une date de creation (ca change plus apres). `final` : une classe ou methode critique (ex: paiement, securite) qu'on veut pas modifier par heritage.

**6.4.a** Erreur fatale (`Call to private method Personne::getAge() from scope Etudiant`). Il faut la mettre en `protected` (ou `public`).

**6.4.b** Pcq `dateNaissance` est `private` dans `Personne`, donc meme les classes filles peuvent pas y toucher direct.

**6.4.c** Ca evite de recopier le meme code deux fois. Si on modifie `__toString()` dans `Personne`, ca s'applique aussi a `Etudiant`.

## Partie 7

**7.1** Dans `un_an()`, il y a une erreur de syntaxe dans `($this->montant 1 * $this->interet / 100)` : il manque un operateur entre `$this->montant` et `1` (ca fait une Parse error).

**7.2** La bonne formule apres 1 an avec un interet de `t` % est :
`montant * (1 + t / 100)` (ou `montant + (montant * t / 100)`).

**7.3** Non c'est pas coherent avec l'enonce qui demande "un montant initial et un interet". Le risque c'est de se tromper a l'appel en ecrivant `new Compte(400, 10)` et du coup ca cree un compte avec 10 DH et 400% d'interet.

## Questions de synthese

**1.** Une classe c'est le modele (le plan de construction), alors qu'un objet c'est une instance concrete creee a partir de cette classe avec `new`.

**2.** Trois avantages de la POO :
- Code mieux organise et plus facile a maintenir (modularite).
- Reutilisation du code grace a l'heritage.
- Protection des donnees grace a l'encapsulation.

**3.** Pour proteger les donnees (encapsulation) et empecher que n'importe qui depuis l'exterieur modifie les attributs avec des valeurs fausses sans controle.

**4.** `private` est accessible uniquement dans la classe elle-meme (meme pas dans les classes filles), tandis que `protected` est accessible dans la classe ET dans ses classes filles.

**5.** Oui, en general les methodes magiques (`__get`, `__set`, `__toString`...) doivent etre `public`. Les exceptions tolerees sont surtout `__construct()` (qu'on peut mettre en `private` ou `protected`, ex: pour un Singleton ou une methode fabrique) et `__clone()` (parfois en `private` pour bloquer le clonage).