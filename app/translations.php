<?php

if(! function_exists("translate_monitoring_activities"))
{
    function translate_monitoring_activities($string,$language="spanish"):String
    {   
        $result='';
        switch ($language)
        {
            case 'spanish':
                switch ($string) 
                {
                    case 'eaten bait':
                        $result='Cebo comido';
                    break;

                    case 'no activity':
                        $result='Sin actividad';
                    break;

                    case 'dead rodent':
                        $result='Roedor Muerto';
                    break;

                    case 'with activity':
                        $result='Con actividad';
                    break;

                    case 'live rodent':
                        $result='Roedor vivo';
                    break;
                    case 'fur':
                        $result='Pelaje';
                    break;
                    case 'footprints':
                        $result='Huellas';
                    break;

          
                }
            break;
        }
        return $result;

    }
    

}


if(! function_exists("translate_conditions"))
{
    function translate_conditions($string,$language="spanish"):String
    {   
        $result='';
        switch ($language)
        {
            case 'spanish':
                switch ($string) 
                {
                    case 'comply':
                        $result='Cumple';
                    break;

                    case 'no comply':
                        $result='No cumple';
                    break;

                    case 'no apply':
                        $result='No aplica';
                    break;
          
                }
            break;
        }
        return $result;

    }
    

}


if(! function_exists("translate_done_not_done"))
{
    function translate_done_not_done($string,$language="spanish"):String
    {   
        $result='';
        switch ($language)
        {
            case 'spanish':
                switch ($string) 
                {
                    case 'done':
                        $result='Realizado';
                    break;

                    case 'not done':
                        $result='No realizado';
                    break;

                }
            break;
        }
        return $result;

    }
    

}


if(! function_exists("translate_yes_no"))
{
    function translate_yes_no($string,$language="spanish"):String
    {   
        $result='';
        switch ($language)
        {
            case 'spanish':
                switch ($string) 
                {
                    case 'yes':
                        $result='Si';
                    break;

                    case 'no':
                        $result='No';
                    break;

                }
            break;
        }
        return $result;

    }
    

}