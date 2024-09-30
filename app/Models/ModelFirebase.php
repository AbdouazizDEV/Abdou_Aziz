<?php

namespace App\Models;

interface ModelFirebase
{
    public function save(array $options = []);
}
