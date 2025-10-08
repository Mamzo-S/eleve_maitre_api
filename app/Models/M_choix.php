<?php

namespace App\Models;

use CodeIgniter\Model;

class M_choix extends Model {
    protected $table = 'choix';
    protected $primaryKey = 'id_choix';
    protected $allowedFields = ['id_eleve', 'code_ief', 'rang'];

}
