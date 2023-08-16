<?php

namespace App\Http\Controllers;

use App\Models\UserData;
use Illuminate\Http\Request;

class KundliController extends Controller
{
    public function matchProfileKundli(Request $request)
    {
        
        $user1 = UserData::where('id', $request->matchingId)->select(["name", "birth_date", "birth_time", "birth_place"])->first();
        $user2 = UserData::where('id', $request->userId)->select(["name", "birth_date", "birth_time", "birth_place"])->first();
        $post_request = array(
            "dp_payload" => array(
                "kundali_id" => -1,
                "kundali_name" => "$user1->name",
                "kundali_date" => date('d/m/Y', strtotime("$user1->birth_date")),
                "kundali_time" => "$user1->birth_time",
                "kundali_city" => "$user1->birth_place",
                "kundali_state" => "NCT",
                "kundali_country" => "India",
                "kundali_latitude" => "28.6358",
                "kundali_longitude" => "77.2244",
                "kundali_elevation" => 212,
                "kundali_olson_timezone" => "Asia/Kolkata",
                "kundali_timezone_offset" => "5.50",
                "second_kundali_id" => -1,
                "second_kundali_name" => "$user2->name",
                "second_kundali_date" => date('d/m/Y', strtotime("$user2->birth_date")),
                "second_kundali_time" => "$user2->birth_time",
                "second_kundali_city" => "$user2->birth_place",
                "second_kundali_state" => "NCT",
                "second_kundali_country" => "India",
                "second_kundali_latitude" => "28.6358",
                "second_kundali_longitude" => "77.2244",
                "second_kundali_elevation" => 212,
                "second_kundali_olson_timezone" => "Asia/Kolkata",
                "second_kundali_timezone_offset" => "5.50",
                "kundali_ayanamsha" => "chitra-paksha",
                "second_kundali_ayanamsha" => "chitra-paksha"
            )
        );

        $url = "https://www.drikpanchang.com/ajax/jyotisha/horoscope-match/dp-horoscope-match-post.php";

        $crl = curl_init();

        $headr = array();
        $headr[] = 'Content-type: application/json';
        $headr[] = 'cookie:dkpwebsitetheme=classic; dkpgeonameid=1261481; dkplanguage=en; dkpschoolname=purnimanta; dkplunarbasecalendar=gregorian; dkpsolarbasecalendar=gregorian; dkpnepalibasecalendar=regional; dkparithmetic=modern; dkpclock=modern; dkpsunrisetype=edges; dkptimeformat=12hour; dkpdaywidgetstate=closed; dkpkundalichartstyle=north; dkpkundalidivisiontype=d1,d9; dkpkundalianchorgraha=lagna,lagna; dkpkundalistringtype=vedic; dkpcmdbitmap=1111111111111111111111111101111; dkpgridview=vertical; dkpnumerallocale=english; dkpplanetposition=true-position; dkpkundalirahutype=mean-rahu; dkpayanamshatype=chitra-paksha; dkptimeprecision=hide-seconds; dkpgeoelevationstatus=disabled; dkpmodernplanetstatus=hidden; dkplongitudestyle=deg-rashi-min-sec; dkppanchangviewtype=table; dkpchartrashitagsstatus=invisible; dkptoolbarstate=open; _ga=GA1.2.1505864004.1648454271; _gid=GA1.2.1054050965.1648454271; __gads=ID=2916e57045f68d37-229c818765d10099:T=1648454270:RT=1648454270:S=ALNI_MbqAmWmE6sL-U178m5SppDyOwP2Fg; PHPSESSID=o1qqlqprs3s0q5tbmdifcj6tqj; dkpfcmtopicbitmap=110000000000000001000000001000000000010110; dkpfcmnotificationrashi=mesha; _gat_gtag_UA_21276953_1=1; FCNEC=[["AKsRol85Jd25vIM9oHlXoUxOJPkvT6m0H8psj8aDnQtYVsmW4BUZhjGkqZ9H_0E328tWyR-2DI2wWREcV4K8w-mUMRf9KeaWaUc0IXZgif3u77n2dAAxePHPkUlHQ7zUBkAmYEXi5hgvc16L_B1iONOzBJroHoSKrw=="],null,[]]';
        $headr[] = 'origin: https://www.drikpanchang.com';
        $headr[] = 'scheme: https';
        $headr[] = 'User-Agent: PostmanRuntime/7.29.0';
        $headr[] = 'authority: www.drikpanchang.com';
        $headr[] = 'referer: https://www.drikpanchang.com/jyotisha/horoscope-match/horoscope-match.html';
        $headr[] = 'sec-ch-ua-mobile: ?1';

        curl_setopt(
            $crl,
            CURLOPT_SSL_VERIFYPEER,
            false
        );

        curl_setopt(
            $crl,
            CURLOPT_URL,
            $url
        );

        curl_setopt(
            $crl,
            CURLOPT_HTTPHEADER,
            $headr
        );

        curl_setopt(
            $crl,
            CURLOPT_POST,
            true
        );

        curl_setopt(
            $crl,
            CURLOPT_POSTFIELDS,
            json_encode($post_request)
        );

        curl_setopt(
            $crl,
            CURLOPT_RETURNTRANSFER,
            true
        );

        $rest = curl_exec($crl);

        $html = preg_match('/<div class="dpLargeNumber">(.*?)<\/div>/s', $rest, $match);

        /*
        <head/><div class="dpResultDivSection"><div class="dpDownloadDiv"><button class="dpDownloadButton"><img src="https://www.drikpanchang.com/images/toolbar/pdf-download/pdf_download_icon.svg" alt="PDF Download" class="dpInfoIcon"><span class="dpInlineBlock">Download PDF</span></button></div><div class="dpPDFSubTitle">Online Kundali Match</div><div class="dpPointsTable"><div class="dpFlex dpTableHeader"><div class="dpCell dpFlexEqual"></div><div class="dpCell dpValueCell dpFlexEqual">Birth Details of Vara</div><div class="dpCell dpValueCell dpFlexEqual">Birth Details of Kanya</div></div><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual">Name</div><div class="dpCardCell dpValueCell dpFlexEqual"><strong>Mukesh Kumar</strong></div><div class="dpCardCell dpValueCell dpFlexEqual"><strong>Arti Khanna</strong></div></div><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual">Date of Birth</div><div class="dpCardCell dpValueCell dpFlexEqual">October 21, 1996, Monday</div><div class="dpCardCell dpValueCell dpFlexEqual">October 1, 1995, Sunday</div></div><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual">Time of Birth</div><div class="dpCardCell dpValueCell dpFlexEqual">04:28 AM</div><div class="dpCardCell dpValueCell dpFlexEqual">12:00 AM</div></div><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual">City Name</div><div class="dpCardCell dpValueCell dpFlexEqual">New Delhi</div><div class="dpCardCell dpValueCell dpFlexEqual">New Delhi</div></div><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual">State Name</div><div class="dpCardCell dpValueCell dpFlexEqual">NCT</div><div class="dpCardCell dpValueCell dpFlexEqual">NCT</div></div><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual">Country Name</div><div class="dpCardCell dpValueCell dpFlexEqual">India</div><div class="dpCardCell dpValueCell dpFlexEqual">India</div></div><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual">Latitude</div><div class="dpCardCell dpValueCell dpFlexEqual">28&#176; 38&#8242; 08&#8243; N (28.6358)</div><div class="dpCardCell dpValueCell dpFlexEqual">28&#176; 38&#8242; 08&#8243; N (28.6358)</div></div><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual">Longitude</div><div class="dpCardCell dpValueCell dpFlexEqual">77&#176; 13&#8242; 27&#8243; E (77.2244)</div><div class="dpCardCell dpValueCell dpFlexEqual">77&#176; 13&#8242; 27&#8243; E (77.2244)</div></div><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual">Olson Timezone</div><div class="dpCardCell dpValueCell dpFlexEqual">Asia/Kolkata</div><div class="dpCardCell dpValueCell dpFlexEqual">Asia/Kolkata</div></div><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual">Ayanamsha</div><div class="dpCardCell dpValueCell dpFlexEqual">Lahiri/Chitrapaksha</div><div class="dpCardCell dpValueCell dpFlexEqual">Lahiri/Chitrapaksha</div></div><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual">Janmarashi (Moonsign)</div><div class="dpCardCell dpValueCell dpFlexEqual">Makara (Capricorn)</div><div class="dpCardCell dpValueCell dpFlexEqual">Dhanu (Sagittarius)</div></div><div class="dpResultFocusPoint"></div><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual">Nakshatra (Birthstar)</div><div class="dpCardCell dpValueCell dpFlexEqual">Shravana (22)</div><div class="dpCardCell dpValueCell dpFlexEqual">Mula (19)</div></div><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual">Lunar Longitude</div><div class="dpCardCell dpValueCell dpFlexEqual">289.88</div><div class="dpCardCell dpValueCell dpFlexEqual">242.24</div></div></div><div class="dpCard dpSnapshotCard"><h3 class="dpResultTitle">Matching Points</h3><div class="dpFlex dpFlexCenter dpProfileBar"><div class="dpFlexEqual dpPersonProfile"><img src="https://www.drikpanchang.com/images/jyotisha/horoscope-match/kundali_boy.svg" alt="Boy" class="dpProfileImg"><div class="dpProfileTitle">Mukesh Kumar</div></div><div class="dpFlexEqual dpPersonProfile"><img src="https://www.drikpanchang.com/images/jyotisha/horoscope-match/heart_break.svg" alt="Match Result" class="dpProfileImg"></div><div class="dpFlexEqual dpPersonProfile"><img src="https://www.drikpanchang.com/images/jyotisha/horoscope-match/kundali_girl.svg" alt="Girl" class="dpProfileImg"><div class="dpProfileTitle">Arti Khanna</div></div></div><div class="dpFlexWrap dpPointsBar"><div class="dpMatchPointIcon"><img src="https://www.drikpanchang.com/images/jyotisha/horoscope-match/union_bad.svg" alt="" class="dpMatchPointImg"></div><div class="dpPointsMessage dpFlexEqual"><div class="dpResultSubtitle">Total Guna Milan</div><div>Union is NOT Recommended</div><div>due to low match points and Bhakuta Dosha</div></div><div class="dpPoints">
        <div class="dpLargeNumber">15</div>
        <div class="dpSmallNumber">36</div></div></div></div><div class="dpKundaliTableWrapper"><div class="dpPointsTable dpPointsTableExt"><div class="dpTableRow dpTableHeader dpFlex"><div class="dpFlexEqual dpCell dpTitleCell">Guna</div><div class="dpCell dpPointCell">Max</div><div class="dpCell dpPointCell">Obt</div><div class="dpFlexEqual dpCell dpPersonCell">Boy</div><div class="dpFlexEqual dpCell dpPersonCell">Girl</div><div class="dpFlexEqual dpCell dpInfoCell">Area of Life</div></div><div class="dpTableRow dpFlex"><div class="dpFlexEqual dpCell dpTitleCell">Varna</div><div class="dpCell dpPointCell">1</div><div class="dpCell dpPointCell">0</div><div class="dpFlexEqual dpCell dpPersonCell">Vaishya</div><div class="dpFlexEqual dpCell dpPersonCell">Kshatriya</div><div class="dpFlexEqual dpCell dpInfoCell">Obedience</div></div><div class="dpTableRow dpFlex"><div class="dpFlexEqual dpCell dpTitleCell">Vashya</div><div class="dpCell dpPointCell">2</div><div class="dpCell dpPointCell">0.5</div><div class="dpFlexEqual dpCell dpPersonCell">Jalachara</div><div class="dpFlexEqual dpCell dpPersonCell">Manava</div><div class="dpFlexEqual dpCell dpInfoCell">Mutual Control</div></div><div class="dpTableRow dpFlex"><div class="dpFlexEqual dpCell dpTitleCell">Tara</div><div class="dpCell dpPointCell">3</div><div class="dpCell dpPointCell">1.5</div><div class="dpFlexEqual dpCell dpPersonCell">Kshema</div><div class="dpFlexEqual dpCell dpPersonCell">Janma</div><div class="dpFlexEqual dpCell dpInfoCell">Luck</div></div><div class="dpTableRow dpFlex"><div class="dpFlexEqual dpCell dpTitleCell">Yoni</div><div class="dpCell dpPointCell">4</div><div class="dpCell dpPointCell">2</div><div class="dpFlexEqual dpCell dpPersonCell">Monkey</div><div class="dpFlexEqual dpCell dpPersonCell">Dog</div><div class="dpFlexEqual dpCell dpInfoCell">Sexual Aspects</div></div><div class="dpTableRow dpFlex"><div class="dpFlexEqual dpCell dpTitleCell">Maitri</div><div class="dpCell dpPointCell">5</div><div class="dpCell dpPointCell">3</div><div class="dpFlexEqual dpCell dpPersonCell">Saturn</div><div class="dpFlexEqual dpCell dpPersonCell">Jupiter</div><div class="dpFlexEqual dpCell dpInfoCell">Affection</div></div><div class="dpTableRow dpFlex"><div class="dpFlexEqual dpCell dpTitleCell">Gana</div><div class="dpCell dpPointCell">6</div><div class="dpCell dpPointCell">0</div><div class="dpFlexEqual dpCell dpPersonCell">Deva</div><div class="dpFlexEqual dpCell dpPersonCell">Rakshasa</div><div class="dpFlexEqual dpCell dpInfoCell">Nature</div></div><div class="dpTableRow dpFlex"><div class="dpFlexEqual dpCell dpTitleCell">Bhakuta</div><div class="dpCell dpPointCell">7</div><div class="dpCell dpPointCell">0</div><div class="dpFlexEqual dpCell dpPersonCell">Capricorn</div><div class="dpFlexEqual dpCell dpPersonCell">Sagittarius</div><div class="dpFlexEqual dpCell dpInfoCell">Love</div></div><div class="dpTableRow dpFlex"><div class="dpFlexEqual dpCell dpTitleCell">Nadi</div><div class="dpCell dpPointCell">8</div><div class="dpCell dpPointCell">8</div><div class="dpFlexEqual dpCell dpPersonCell">Antya</div><div class="dpFlexEqual dpCell dpPersonCell">Aadi</div><div class="dpFlexEqual dpCell dpInfoCell">Health</div></div></div></div><div class="dpTableHint dpSmallText"><span class="dpTableHintCell dpInlineBlock"><small>*Max - Maximum Point</small></span><span class="dpTableHintCell dpInlineBlock"><small>*Obt - Obtained Point</small></span></div><div class="dpPDFSubTitle">Online Kundali Match</div><div class="dpResultCardSection dpFlexWrap"><div class="dpCard dpResultCard dpFlexEqual"><h3 class="dpResultTitle">Varna Kuta Details</h3><div class="dpCardMiddleInfo">Varna Kuta Points: <span class="dpCardPointValue">0</span>/1</div><div class="dpCardTableInfo"><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual dpBoyName">mukesh kumar</div><div class="dpCardCell dpValueCell dpFlexEqual">Vaishya</div></div><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual dpGirlName">arti khanna</div><div class="dpCardCell dpValueCell dpFlexEqual">Kshatriya</div></div></div><div class="dpFlex"><div class="dpInfoIcon"><img src="https://www.drikpanchang.com/images/icon/utilities/info_icon.svg" alt="Info" class="dpInfoIconImg"></div><div class="dpFlexEqual dpCardInfo">Varna Kuta is assigned 1 point. Varna Kuta represents mutual love, comfort and <strong>obedience</strong>. Grade of spiritual development also depends on Varna Kuta.</div></div></div><div class="dpCard dpResultCard dpFlexEqual"><h3 class="dpResultTitle">Vashya Kuta Details</h3><div class="dpCardMiddleInfo">Vashya Kuta Points: <span class="dpCardPointValue">0.5</span>/2</div><div class="dpCardTableInfo"><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual dpBoyName">mukesh kumar</div><div class="dpCardCell dpValueCell dpFlexEqual">Jalachara</div></div><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual dpGirlName">arti khanna</div><div class="dpCardCell dpValueCell dpFlexEqual">Manava</div></div></div><div class="dpFlex"><div class="dpInfoIcon"><img src="https://www.drikpanchang.com/images/icon/utilities/info_icon.svg" alt="Info" class="dpInfoIconImg"></div><div class="dpFlexEqual dpCardInfo">Vashya Kuta is assigned 2 points. Vashya Kuta represents <strong>mutual control</strong> or dominance. It also shows friendship and amenability between the couple.</div></div></div><div class="dpCard dpResultCard dpFlexEqual"><h3 class="dpResultTitle">Tara Kuta Details</h3><div class="dpCardMiddleInfo">Tara Kuta Points: <span class="dpCardPointValue">1.5</span>/3</div><div class="dpCardTableInfo"><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual dpBoyName">mukesh kumar</div><div class="dpCardCell dpValueCell dpFlexEqual">Kshema</div></div><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual dpGirlName">arti khanna</div><div class="dpCardCell dpValueCell dpFlexEqual">Janma</div></div></div><div class="dpFlex"><div class="dpInfoIcon"><img src="https://www.drikpanchang.com/images/icon/utilities/info_icon.svg" alt="Info" class="dpInfoIconImg"></div><div class="dpFlexEqual dpCardInfo">Tara Kuta is assigned 3 points. Tara Kuta represents <strong>luck</strong>, auspiciousness and transmission of mutual beneficence between the couple.</div></div></div><div class="dpCard dpResultCard dpFlexEqual"><h3 class="dpResultTitle">Yoni Kuta Details</h3><div class="dpCardMiddleInfo">Yoni Kuta Points: <span class="dpCardPointValue">2</span>/4</div><div class="dpCardTableInfo"><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual dpBoyName">mukesh kumar</div><div class="dpCardCell dpValueCell dpFlexEqual">Monkey</div></div><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual dpGirlName">arti khanna</div><div class="dpCardCell dpValueCell dpFlexEqual">Dog</div></div></div><div class="dpFlex"><div class="dpInfoIcon"><img src="https://www.drikpanchang.com/images/icon/utilities/info_icon.svg" alt="Info" class="dpInfoIconImg"></div><div class="dpFlexEqual dpCardInfo">Yoni Kuta is assigned 4 points. Yoni Kuta, as name suggests, represents <strong>sexual aspects</strong> including sexual urge and copulatory organs.</div></div></div><div class="dpCard dpResultCard dpFlexEqual"><h3 class="dpResultTitle">Maitri Kuta Details</h3><div class="dpCardMiddleInfo">Maitri Kuta Points: <span class="dpCardPointValue">3</span>/5</div><div class="dpCardTableInfo"><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual dpBoyName">mukesh kumar</div><div class="dpCardCell dpValueCell dpFlexEqual">Saturn</div></div><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual dpGirlName">arti khanna</div><div class="dpCardCell dpValueCell dpFlexEqual">Jupiter</div></div></div><div class="dpFlex"><div class="dpInfoIcon"><img src="https://www.drikpanchang.com/images/icon/utilities/info_icon.svg" alt="Info" class="dpInfoIconImg"></div><div class="dpFlexEqual dpCardInfo">Maitri Kuta is assigned 5 points. Graha Maitri represents psychological disposition, mental qualities and <strong>affection</strong> between the couple.</div></div></div><div class="dpCard dpResultCard dpFlexEqual"><h3 class="dpResultTitle">Gana Kuta Details</h3><div class="dpCardMiddleInfo">Gana Kuta Points: <span class="dpCardPointValue">0</span>/6</div><div class="dpCardTableInfo"><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual dpBoyName">mukesh kumar</div><div class="dpCardCell dpValueCell dpFlexEqual">Deva</div></div><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual dpGirlName">arti khanna</div><div class="dpCardCell dpValueCell dpFlexEqual">Rakshasa</div></div></div><div class="dpFlex"><div class="dpInfoIcon"><img src="https://www.drikpanchang.com/images/icon/utilities/info_icon.svg" alt="Info" class="dpInfoIconImg"></div><div class="dpFlexEqual dpCardInfo">Gana Kuta is assigned 6 points. Gana Kuta represents <strong>nature</strong>, longevity, wealth, prosperity and love.</div></div></div><div class="dpCard dpResultCard dpFlexEqual"><h3 class="dpResultTitle">Bhakuta Kuta Details</h3><div class="dpCardMiddleInfo">Bhakuta Kuta Points: <span class="dpCardPointValue">0</span>/7</div><div class="dpCardTableInfo"><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual dpBoyName">mukesh kumar</div><div class="dpCardCell dpValueCell dpFlexEqual">Capricorn</div></div><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual dpGirlName">arti khanna</div><div class="dpCardCell dpValueCell dpFlexEqual">Sagittarius</div></div></div><div class="dpFlex"><div class="dpInfoIcon"><img src="https://www.drikpanchang.com/images/icon/utilities/info_icon.svg" alt="Info" class="dpInfoIconImg"></div><div class="dpFlexEqual dpCardInfo">Bhakuta Kuta is assigned 7 points. Bhakuta Kuta represents children, wealth, comforts, good luck and growth of the family.</div></div></div><div class="dpCard dpResultCard dpFlexEqual"><h3 class="dpResultTitle">Nadi Kuta Details</h3><div class="dpCardMiddleInfo">Nadi Kuta Points: <span class="dpCardPointValue">8</span>/8</div><div class="dpCardTableInfo"><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual dpBoyName">mukesh kumar</div><div class="dpCardCell dpValueCell dpFlexEqual">Antya</div></div><div class="dpFlex dpCardDetailsRow"><div class="dpCardCell dpFlexEqual dpGirlName">arti khanna</div><div class="dpCardCell dpValueCell dpFlexEqual">Aadi</div></div></div><div class="dpFlex"><div class="dpInfoIcon"><img src="https://www.drikpanchang.com/images/icon/utilities/info_icon.svg" alt="Info" class="dpInfoIconImg"></div><div class="dpFlexEqual dpCardInfo">Nadi Kuta is assigned 8 points. Nadi Kuta represents temperament, nervous energy, and affliction.</div></div></div></div><div class="dpDownloadDiv"><button class="dpDownloadButton"><img src="https://www.drikpanchang.com/images/toolbar/pdf-download/pdf_download_icon.svg" alt="PDF Download" class="dpInfoIcon"><span class="dpInlineBlock">Download PDF</span></button></div></div>
        */

        $response_ret = array(
            "success" => true,
            "data" => array(
                "varna" => array(
                    "description" => "Natural Refinement  / Work",
                    "male_koot_attribute" => "Shoodra",
                    "female_koot_attribute" => "Vipra",
                    "total_points" => 1,
                    "received_points" => 0
                ),
                "vashya" => array(
                    "description" => "Innate Giving / Attraction  towards each other",
                    "male_koot_attribute" => "Maanav",
                    "female_koot_attribute" => "Jalchar",
                    "total_points" => 2,
                    "received_points" => 1
                ),
                "tara" => array(
                    "description" => "Comfort - Prosperity - Health",
                    "male_koot_attribute" => "Punarvasu",
                    "female_koot_attribute" => "Uttra Bhadrapad",
                    "total_points" => 3,
                    "received_points" => 3
                ),
                "yoni" => array(
                    "description" => "Intimate Physical",
                    "male_koot_attribute" => "Marjaar",
                    "female_koot_attribute" => "Gau",
                    "total_points" => 4,
                    "received_points" => 2
                ),
                "maitri" => array(
                    "description" => "Friendship",
                    "male_koot_attribute" => "Mercury",
                    "female_koot_attribute" => "Jupiter",
                    "total_points" => 5,
                    "received_points" => 1
                ),
                "gan" => array(
                    "description" => "Temperament",
                    "male_koot_attribute" => "Dev",
                    "female_koot_attribute" => "Manushya",
                    "total_points" => 6,
                    "received_points" => 6
                ),
                "bhakut" => array(
                    "description" => "Constructive Ability / Constructivism / Society and Couple",
                    "male_koot_attribute" => "Gemini",
                    "female_koot_attribute" => "Pisces",
                    "total_points" => 7,
                    "received_points" => 7
                ),
                "nadi" => array(
                    "description" => "Progeny / Excess",
                    "male_koot_attribute" => "Adi",
                    "female_koot_attribute" => "Madhya",
                    "total_points" => 8,
                    "received_points" => 8
                ),
                "total" => array(
                    "total_points" => 36,
                    "received_points" => $match[1],
                    "minimum_required" => 18
                ),
                "conclusion" => array(
                    "status" => false,
                    "report" => "The match has scored $match[1] points outs of 36 points."
                )
            )
        );
        $response = json_encode($response_ret);
        return response()->json($response_ret);
    }

    public function astroYogiCurlCall(Request $request)
    {
        $boy_birth = explode(" ", $request->user_birth_date);
        $boy_birth_date = $boy_birth[0];
        $boy_birth_time = explode(":", $boy_birth[1]);
        $exploded_bdate_boy = explode("-", $boy_birth_date);
        //dd($exploded_bdate_boy);
        $b_year = $exploded_bdate_boy[0];
        $b_mon = $exploded_bdate_boy[1];
        $b_date = $exploded_bdate_boy[2];

        //girl
        $girl_birth = explode(" ", $request->matching_dob);
        $girl_birth_date = $girl_birth[0];
        $girl_birth_time = explode(":", $girl_birth[1]);
        $exploded_bdate_girl = explode("-", $girl_birth_date);
        $g_year = $exploded_bdate_girl[0];
        $g_mon = $exploded_bdate_girl[1];
        $g_date = $exploded_bdate_girl[2];

        $to_be_decoded_data = array(
            "m_day" => $b_date,
            "m_month" => $b_mon,
            "m_year" => $b_year,
            "m_hour" => $boy_birth_time[0],
            "m_min" => $boy_birth_time[1],
            "m_lat" => "28.8527",
            "m_lon" => "77.2982",
            "m_tzone" => "5.5",
            "f_day" => $g_date,
            "f_month" => $g_mon,
            "f_year" => $g_year,
            "f_hour" => $girl_birth_time[0],
            "f_min" => $girl_birth_time[1],
            "f_lat" => "28.8527",
            "f_lon" => "77.2982",
            "f_tzone" => "5.5",
        );
        $data_object = urlencode(urlencode(json_encode($to_be_decoded_data)));

        // astro yogi curl
        $curl = curl_init();
        /*
            "%7B%22m_day%22%3A%2221%22%2C%22m_month%22%3A%2210%22%2C%22m_year%22%3A%221996%22%2C%22m_hour%22%3A%2204%22%2C%22m_min%22%3A%2228%22%2C%22m_lat%22%3A%2228.8527%22%2C%22m_lon%22%3A%2277.2982%22%2C%22m_tzone%22%3A%225.5%22%2C%22f_day%22%3A%2201%22%2C%22f_month%22%3A%2210%22%2C%22f_year%22%3A%221995%22%2C%22f_hour%22%3A%2200%22%2C%22f_min%22%3A%2200%22%2C%22f_lat%22%3A%2228.8527%22%2C%22f_lon%22%3A%2277.2982%22%2C%22f_tzone%22%3A%225.5%22%7D"
        */
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => "https://www.astroyogi.com/contentsyn/kundli/getmatchingdoshadetails?objStr=$data_object",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                "Content-Length" =>   1024,
                CURLOPT_HTTPHEADER => array(
                    'authority: www.astroyogi.com',
                    'Cookie: .AspNetCore.Cookies=CfDJ8PnzSTt_SflEswqxktU98dSseYeZsMbCaoS2pRkSI-BftlM1P-ToxEIk910RMhDDKEh6W9KCAbF2lorQiXFaDgcwJJeBXntM0YYBMnYpv-aeOvpEKcaRRL5dMDgsI1nnYDLFfP6Gg-6gGn1tXR5Cdaj1ae_rzOUsRmU_XMeQzu5ngjfPtalVXyIMHTzKeDslnkPnvTNnSDzNdMcHIfNG6ZuPH_9L-JzJmvCGdasi8qg69LrYETZiqbwvgDvNr8KMat2Vwcm7QUUxplgFDYXuVuGAL1UVoN0jrF1S7L6y6E2gDh-bKhIWo-jlQgtKYW0B8Ik410IgcTuM0EfSfGENoLG_OZuH8CZ3b0tYX5rdol5bBqhE6zEm9jFKOAzhDeUSqYqEGBk6yG6YExIbPFB4iRBLbiIyE4ygiyX9fFEhCQ9_O4kGh8henlRM3FlCqQfLhp--Aj2CsRGZm2AoDo2I0GBn2c4pN0IqDcuTWtyAcEQGYtTqw0lYb6nNTl06L597BagaE8_mEIu67NZgzGK_DmI1ciVbQeZ2M7gMXG2SH4rmfJbbuF4zKGVpyQpQ5s1OTNUCKf4Rk-ikYo3K8evelYY8z2jyIJ5gNAjF_PKWZ5q0oH--JrimvERIqJ3bxJMOI-UxKpGllnScU8ZnGrnEJLS43QHe53NRFZEkLgP7XAZCLxw6T9gOL6C_z1UAdVBxyvIapQgTUn0628lw-gMtreudkRHEks6ieAeu1TeLRAri7JbAQn8bglt7SSpWiqJg2A'
                ),
            )
        );

        $response = curl_exec($curl);
        dd($response);
        curl_close($curl);
    }
}
