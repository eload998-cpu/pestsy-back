<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Administration\{Country,City,State};

class CountryLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        State::truncate();
        Country::truncate();
        City::truncate();


        $url = public_path('countries_states_cities.json');
        $content = json_decode(file_get_contents($url),true);

        foreach($content as $c)
        {
            $country=Country::create(
                [
                    "name"=>$c["name"]
                ]);


                foreach($c["states"] as $st)
                {
                    $state=State::create(
                        [
                            "name"=>$st["name"],
                            "country_id"=>$country["id"]
                        ]);


                       foreach ($st["cities"] as $ct) 
                       {
                        $city=City::create(
                            [
                                "name"=>$ct["name"],
                                "state_id"=>$state["id"]
                            ]);
    
                       } 
                }

        
        }

    }
}
