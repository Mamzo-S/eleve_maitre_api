<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\M_eleve;

class GenerateElevePasswords extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'generate:eleve-passwords';
    protected $description = 'Génère et met à jour un mot de passe aléatoire pour chaque élève et enregistre les mots de passe en clair dans writable/.';

    public function run(array $params)
    {
        $fileOption = CLI::getOption('file') ?? 'passwords_generated.txt';
        $append = CLI::getOption('append') !== null;

        $filePath = WRITEPATH . $fileOption;

        $eleveModel = new M_eleve();
        $eleves = $eleveModel->findAll();
        $updated = 0;

        // Préparer le fichier
        try {
            if (!$append && file_exists($filePath)) {
                file_put_contents($filePath, "");
            }
            $header = "=== Mots de passe générés le " . date('Y-m-d H:i:s') . " ===\n";
            file_put_contents($filePath, $header, FILE_APPEND | LOCK_EX);
        } catch (\Throwable $e) {
            CLI::error("Impossible d'écrire dans le fichier : {$filePath}. Vérifie les permissions (writable/).");
            CLI::error($e->getMessage());
            return;
        }

        foreach ($eleves as $eleve) {
            // on vérifie si le champ password est vide OU vaut null
            if (empty($eleve['password'])) {
                $password = $this->generateRandomPassword(10);

                // Met à jour la BDD : stocke le hash
                $eleveModel->update($eleve['id_eleve'], [
                    'password' => password_hash($password, PASSWORD_DEFAULT)
                ]);

                // Ecrit ligne informative dans le fichier
                $line = "Élève #{$eleve['id_eleve']} ({$eleve['nom_eleve']} {$eleve['prenom_eleve']}): $password\n";
                file_put_contents($filePath, $line, FILE_APPEND | LOCK_EX);

                // affiche en terminal (optionnel)
                CLI::write(trim($line), 'green');
                $updated++;
            }
        }
    }

    private function generateRandomPassword($length = 8)
    {
        $bytes = (int) ceil($length / 2);
        $hex = bin2hex(random_bytes($bytes));
        return substr($hex, 0, $length);
    }
}
