<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\M_eleve;

class GenerateElevePasswords extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'generate:eleve-passwords';
    protected $description = 'Génère et met à jour un mot de passe aléatoire pour chaque élève.';

    public function run(array $params)
    {
        $eleveModel = new M_eleve();
        $eleves = $eleveModel->findAll();
        $updated = 0;

        foreach ($eleves as $eleve) {
            // je verifie si le champs mot de passe est vide avant de generer quoi que ce soit
            if (empty($eleve['password'])) {
                $password = $this->generateRandomPassword(10);
                $eleveModel->update($eleve['id_eleve'], [
                    'password' => password_hash($password, PASSWORD_DEFAULT)
                ]);
                CLI::write("Élève #{$eleve['id_eleve']} : $password", 'green');
                $updated++;
            }
        }
        CLI::write("$updated mots de passe générés et mis à jour.", 'yellow');
    }

    private function generateRandomPassword($length = 8)
    {
        return bin2hex(random_bytes($length / 2));
    }
}
