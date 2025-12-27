<?php
// common.php

// use App\Models\GeneralSetting; // <-- LIGNE SUPPRIMÉE

if (!function_exists('settings')) {
    /**
     * Fetches the general settings.
     * (Modifié pour ne pas appeler le modèle inexistant)
     *
     * @return null
     */
    function settings()
    {
        // Renvoie null car nous n'utilisons pas ce modèle
        return null;
    }
}
