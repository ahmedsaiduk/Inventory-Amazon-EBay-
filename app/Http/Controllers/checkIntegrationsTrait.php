<?php
namespace App\Http\Controllers;

trait checkIntegrationsTrait 
{
	protected function checkIntegrations($user)
    {
        $integrations = [];

        foreach ($user->integrations as $integration) 
        {
            $integrations [$integration->marketPlace][] = $integration->site;  
        }

        return $integrations;
    }
}