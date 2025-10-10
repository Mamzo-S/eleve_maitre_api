<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\M_eleve;

class GenerateElevePasswords extends BaseCommand
{
    protected $group = 'Custom';
    protected $name = 'generate:eleve-passwords';
    protected $description = 'Génère et met à jour un mot de passe aléatoire pour chaque élève et enregistre les mots de passe en clair dans writable/.';

    public function run(array $params)
    {
        $eleveModel = new M_eleve();
        $eleves = $eleveModel->findAll();
        $updated = 0;

        // Ouvre un fichier texte pour stocker les mots de passe générés
        $filePath = WRITEPATH . 'passwords_' . date('Ymd_His') . '.txt';
        $handle = fopen($filePath, 'w');

        if (!$handle) {
            CLI::error("Impossible d’ouvrir le fichier pour écrire les mots de passe !");
            return;
        }

        foreach ($eleves as $eleve) {
            if (empty($eleve['password'])) {
                $password = $this->generateRandomPassword(10);
                $hashed = password_hash($password, PASSWORD_DEFAULT);

                $eleveModel->update($eleve['id_eleve'], ['password' => $hashed]);
                $updated++;

                // Écrit dans le fichier
                fwrite($handle, "Élève #{$eleve['id_eleve']} : $password\n");
                CLI::write("Élève #{$eleve['id_eleve']} : $password", 'green');
            }
        }

        // Ferme proprement le fichier
        fclose($handle);

        CLI::write("$updated mots de passe générés et enregistrés.", 'yellow');
        CLI::write("Fichier enregistré : $filePath", 'blue');
    }

    private function generateRandomPassword($length = 8)
    {
        $bytes = (int) ceil($length / 2);
        $hex = bin2hex(random_bytes($bytes));
        return substr($hex, 0, $length);
    }
}
