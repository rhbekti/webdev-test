<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BilanganController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bilangan' => 'required|numeric'
        ]);

        $bilangan = $request->input('bilangan');
        $showBageConcat = 0;
        $showBage = 0;
        $showConcat = 0;
        $i = 1;

        while ($i <= $bilangan) {
            if ($showBageConcat < 5) {
                if ($i % 3 == 0 && $i % 5 == 0) {
                    echo $i . " Bage Concat </br>";
                    $showBageConcat++;
                } else if ($i % 5 == 0) {
                    echo $i . " Concat </br>";
                    $showConcat++;
                } else if ($i % 3 == 0) {
                    echo $i . " Bage </br>";
                    $showBage++;
                }
                $i++;
            } else {
                break;
            }
        }

        echo "----------------------------------</br>";
        echo "total bage = " . $showBage . "</br>";
        echo "total concat = " . $showConcat . "</br>";
        echo "total bage concat = " . $showBageConcat . "</.br>";
    }
}
