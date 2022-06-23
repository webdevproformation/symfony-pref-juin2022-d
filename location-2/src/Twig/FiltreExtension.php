<?php 

namespace App\Twig ;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FiltreExtension extends AbstractExtension{

    public function getFilters(){

        // <td class="text-end align-middle">{{vehicule.prixJournalier | deviseFr}}</td>
        return [
            new TwigFilter("deviseFr" , [$this , "deviseFr"]),
            new TwigFilter("role" , [$this, "role"]),
            new TwigFilter("civilite" , [$this, "civilite"]),

        ];
    }

    public function deviseFr(float $prix) :string {

        return number_format($prix , 0 , "," , " ") . " €";

    }

    public function role(array $roles) : string{

        if(isset($roles[0])){
            return strtolower(str_replace("ROLE_" , "", $roles[0]));
        }
        return "";
    }

    public function civilite (string $civilite): string{
        if($civilite === "f"){
            return "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-gender-female\" viewBox=\"0 0 16 16\">
            <path fill-rule=\"evenodd\" d=\"M8 1a4 4 0 1 0 0 8 4 4 0 0 0 0-8zM3 5a5 5 0 1 1 5.5 4.975V12h2a.5.5 0 0 1 0 1h-2v2.5a.5.5 0 0 1-1 0V13h-2a.5.5 0 0 1 0-1h2V9.975A5 5 0 0 1 3 5z\"/>
          </svg>";
        }
        return "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-gender-male\" viewBox=\"0 0 16 16\">
        <path fill-rule=\"evenodd\" d=\"M9.5 2a.5.5 0 0 1 0-1h5a.5.5 0 0 1 .5.5v5a.5.5 0 0 1-1 0V2.707L9.871 6.836a5 5 0 1 1-.707-.707L13.293 2H9.5zM6 6a4 4 0 1 0 0 8 4 4 0 0 0 0-8z\"/>
      </svg>";

    }

}