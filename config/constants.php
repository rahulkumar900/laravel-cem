<?php

return [

    // roles
    'roles_string' => [
        '0'     =>      'matchmaker',
        '1'     =>      'customer support',
        '2'     =>      'approvals',
        '3'     =>      'relationship mananger',
        '4'     =>      'hr',
        '5'     =>      'telesales',
        '6'     =>      '',
        '7'     =>      'team leader',
        '8'     =>      '',
        '9'     =>      'admin',
        '10'    =>      '',
        '11'    =>      'work from home',
        '12'    =>      'marketting',
    ],

    'roles_ineger' => [
        'matchmaker'            =>      '0',
        'customer_support'      =>      '1',
        'approvals'             =>      '2',
        'relationship_mananger' =>      '3',
        'hr'                    =>      '4',
        'telesales'             =>      '5',
        '6'                     =>      '',
        'team_leader'           =>      '7',
        '8'                     =>      '',
        'admin'                 =>      '9',
        '10'                    =>      '',
        'work_from_home'        =>      '11',
        'marketting'            =>      '12',
    ],


    // lead types
    'lead_types'    => [
        '0'     =>      'website leads',
        '1'     =>      'facebook leads',
        '2'     =>      'operator calls',
        '3'     =>      'crm leads'
    ],

    // income dropdown
    'income_ranges' => [
        [0, "No Income"],
        [1.25, "1.0-2.5 Lakh/Year"],
        [3.75, "2.5-5.0 Lakh/Year"],
        [6.25, "5.0-7.5 Lakh/Year"],
        [8.75, "7.5-10.0 Lakh/Year"],
        [12.50, "10.0-15.0 Lakh/Year"],
        [17.50, "15.0-20.0 Lakh/Year"],
        [22.50, "20.0-25.0 Lakh/Year"],
        [27.50, "25.0-30.0 Lakh/Year"],
        [42.50, "35.0-50.0 Lakh/Year"],
        [60.0, "50.0-70.0 Lakh/Year"],
        [85.00, "70.0-100.0 Lakh/Year"],
        [100.00, "1Cr+ /Year"],
    ],

    // no image available
    'no_image' => ['https://hans-matrimony.s3.ap-south-1.amazonaws.com/No_Image_Available.jpg']
];
