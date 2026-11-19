<?php

// 2.1 et 2.3 : Classe Voiture avec promotion de proprietes et destructeur
class Voiture {
    public function __construct(
        public string $couleur = "Blanche",
        public int $vitesse = 0
    ) {
        echo "Voiture {$this->couleur} creee." . PHP_EOL;
    }

    public function afficher(): void {
        echo "Couleur : {$this->couleur} | Vitesse : {$this->vitesse} km/h" . PHP_EOL;
    }

    public function __destruct() {
        echo "Voiture {$this->couleur} detruite." . PHP_EOL;
    }
}

$v1 = new Voiture();
$v2 = new Voiture("Rouge", 50);
$v3 = new Voiture("Noire");
$v1->afficher();
$v2->afficher();
$v3->afficher();

$a = new Voiture("Bleue");
$b = new Voiture("Verte");

// 2.2 et 2.3.d : Classe Personne avec valeurs par defaut et destructeur
class Personne {
    public function __construct(
        public string $nom = "",
        public string $prenom = ""
    ) {
        if ($nom === "" && $prenom === "") {
            echo "Creation " . __CLASS__ . PHP_EOL;
        }
    }

    public static function depuisTableau(array $data): self {
        return new self($data['nom'] ?? "", $data['prenom'] ?? "");
    }

    public function __destruct() {
        echo "Detruire " . __CLASS__ . PHP_EOL;
    }
}

$p1 = new Personne();
$p2 = new Personne("Hachimi", "Sara");
$p3 = Personne::depuisTableau(['nom' => 'Alami', 'prenom' => 'Yassine']);

echo "Fin du script." . PHP_EOL;