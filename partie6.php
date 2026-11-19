<?php

// 6.1
class Animal {
    public function manger(): void {
        echo "Je mange." . PHP_EOL;
    }
}

class Chien extends Animal {
    public function aboyer(): void {
        echo "Wouf !" . PHP_EOL;
    }
}

class Chat extends Animal {
    public function miauler(): void {
        echo "Miaou !" . PHP_EOL;
    }
}

$c = new Chien();
$c->manger();
$c->aboyer();

$chat = new Chat();
$chat->manger();
$chat->miauler();

// 6.3
class Base {
    final public function afficher(): void {
        echo "Methode finale" . PHP_EOL;
    }
}

final class Outil {
    public const VERSION = "1.0";
}

class Produit {
    public function __construct(public readonly string $reference) {}
}

$p = new Produit("REF-001");
echo $p->reference . PHP_EOL;

// 6.4
class Personne {
    public string $nom;
    public string $prenom;
    private string $dateNaissance;
    private string $villeNaissance;
    private string $cin;

    public function __construct(
        string $nom,
        string $prenom,
        string $dateNaissance,
        string $villeNaissance,
        string $cin
    ) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->dateNaissance = $dateNaissance;
        $this->villeNaissance = $villeNaissance;
        $this->cin = $cin;
    }

    protected function getAge(): int {
        $naissance = new DateTime($this->dateNaissance);
        $aujourdhui = new DateTime();
        return $naissance->diff($aujourdhui)->y;
    }

    public function __toString(): string {
        return "{$this->prenom} {$this->nom} ({$this->getAge()} ans)";
    }
}

class Etudiant extends Personne {
    private string $cne;

    public function __construct(
        string $nom,
        string $prenom,
        string $dateNaissance,
        string $villeNaissance,
        string $cin,
        string $cne
    ) {
        parent::__construct($nom, $prenom, $dateNaissance, $villeNaissance, $cin);
        $this->cne = $cne;
    }

    public function getCne(): string {
        return $this->cne;
    }

    public function __toString(): string {
        return parent::__toString() . " - CNE : " . $this->cne;
    }
}

$etudiants = [
    new Etudiant("Hachimi", "Sara", "2002-05-14", "Marrakech", "EE123456", "G130001"),
    new Etudiant("Alami", "Yassine", "2001-11-02", "Casablanca", "BE987654", "D140022"),
    new Etudiant("Bennani", "Salma", "2003-03-20", "Rabat", "A554433", "J120088"),
    new Etudiant("Tazi", "Omar", "2002-08-10", "Fes", "CD112233", "S130045"),
    new Etudiant("Idrissi", "Kenza", "2001-01-25", "Tanger", "KB445566", "P110099")
];

foreach ($etudiants as $e) {
    echo $e . PHP_EOL;
}