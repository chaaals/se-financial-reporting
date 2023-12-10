<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('report_templates', function (Blueprint $table) {
            $table->string('template_name', 20)
                ->primary();
            $table->longText('template');
        });

        DB::table('report_templates')->insert([
            [
                'template_name' => 'tb_pre',
                'template' => '{"1 01 01 010": "14", "1 01 01 020": "16", "1 01 02 010": "18", "1 01 02 020": "19", "1 01 03 020": "22", "1 02 01 010": "20", "1 02 05 010": "28", "1 03 01 010": "34", "1 03 01 070": "35", "1 03 03 010": "37", "1 03 03 030": "40", "1 03 05 020": "42", "1 03 05 030": "43", "1 03 05 040": "44", "1 03 06 010": "46", "1 03 06 020": "47", "1 03 06 990": "48", "1 03 01 011": "59", "1 04 04 010": "63", "1 04 04 020": "64", "1 04 04 060": "65", "1 04 04 070": "66", "1 04 04 990": "67", "1 05 01 010": "77", "1 05 01 050": "78", "1 05 01 990": "79", "1 07 04 020": "90", "1 07 04 021": "91", "1 07 04 990": "92", "1 07 04 991": "93", "1 07 05 010": "107", "1 07 05 011": "108", "1 07 05 020": "109", "1 07 05 021": "110", "1 07 05 030": "111", "1 07 05 031": "112", "1 07 05 070": "113", "1 07 05 071": "114", "1 07 05 090": "115", "1 07 05 091": "116", "1 07 05 100": "117", "1 07 05 101": "118", "1 07 05 110": "120", "1 07 05 111": "121", "1 07 05 130": "122", "1 07 05 131": "123", "1 07 05 140": "124", "1 07 05 141": "125", "1 07 05 990": "126", "1 07 05 991": "127", "1 07 06 010": "132", "1 07 06 011": "133", "1 07 07 010": "139", "1 07 07 011": "140", "1 07 07 020": "141", "1 07 07 021": "142", "1 07 10 020": "152", "1 07 10 030": "153", "1 07 99 090": "147", "1 07 99 990": "148", "1 07 99 991": "149", "2 01 01 010": "168", "2 01 01 020": "169", "2 02 01 010": "179", "2 02 01 020": "180", "2 02 01 030": "181", "2 02 01 040": "182", "2 04 01 010": "193", "2 04 01 040": "194", "2 04 01 050": "195", "2 05 01 990": "200", "2 99 99 990": "205", "3 01 01 010": "213", "3 01 01 020": "214", "4 02 01 040": "224", "4 02 01 980": "225", "4 02 01 990": "226", "4 02 02 010": "241", "4 02 02 020": "242", "4 02 02 050": "250", "4 02 02 220": "251", "4 02 02 990": "253", "4 03 01 020": "261", "4 04 02 010": "272", "4 04 02 020": "273", "4 06 01 010": "278", "5 01 01 010": "290", "5 01 01 020": "291", "5 01 02 010": "296", "5 01 02 020": "297", "5 01 02 030": "298", "5 01 02 040": "299", "5 01 02 050": "300", "5 01 02 060": "301", "5 01 02 080": "302", "5 01 02 100": "303", "5 01 02 110": "304", "5 01 02 120": "305", "5 01 02 130": "306", "5 01 02 140": "307", "5 01 02 150": "308", "5 01 02 990": "309", "5 01 03 010": "312", "5 01 03 020": "313", "5 01 03 030": "314", "5 01 03 040": "315", "5 01 04 030": "318", "5 01 04 990": "319", "5 02 01 010": "329", "5 02 01 020": "330", "5 02 02 010": "333", "5 02 03 010": "339", "5 02 03 020": "340", "5 02 03 070": "342", "5 02 03 080": "343", "5 02 03 090": "344", "5 02 03 990": "345", "5 02 04 010": "355", "5 02 04 020": "356", "5 02 05 010": "368", "5 02 05 020": "369", "5 02 05 030": "370", "5 02 05 040": "371", "5 02 10 030": "376", "5 02 11 030": "380", "5 02 11 990": "381", "5 02 12 020": "384", "5 02 12 030": "385", "5 02 13 040": "394", "5 02 13 050": "395", "5 02 13 060": "396", "5 02 13 070": "397", "5 02 16 020": "404", "5 02 16 030": "405", "5 02 99 010": "414", "5 02 99 020": "415", "5 02 99 030": "416", "5 02 99 050": "417", "5 02 99 060": "418", "5 02 99 070": "419", "5 02 99 990": "420", "5 03 01 040": "427", "5 05 01 040": "432", "5 05 01 050": "433", "5 05 01 060": "434", "5 05 01 070": "435", "5 05 01 090": "436", "5 05 01 990": "437", "5 05 03 060": "439", "5 05 04 990": "440"}',
            ],
            [
                'template_name' => 'tb_post',
                'template' => '{"1 01 01 010": "14", "1 01 01 020": "16", "1 01 02 010": "18", "1 01 02 020": "19", "1 01 03 020": "22", "1 02 01 010": "20", "1 02 05 010": "28", "1 03 01 010": "34", "1 03 01 070": "35", "1 03 03 010": "37", "1 03 03 030": "40", "1 03 05 020": "42", "1 03 05 030": "43", "1 03 05 040": "44", "1 03 06 010": "46", "1 03 06 020": "47", "1 03 06 990": "48", "1 03 01 011": "59", "1 04 04 010": "63", "1 04 04 020": "64", "1 04 04 060": "65", "1 04 04 070": "66", "1 04 04 990": "67", "1 05 01 010": "77", "1 05 01 050": "78", "1 05 01 990": "79", "1 07 04 020": "90", "1 07 04 021": "91", "1 07 04 990": "92", "1 07 04 991": "93", "1 07 05 010": "107", "1 07 05 011": "108", "1 07 05 020": "109", "1 07 05 021": "110", "1 07 05 030": "111", "1 07 05 031": "112", "1 07 05 070": "113", "1 07 05 071": "114", "1 07 05 090": "115", "1 07 05 091": "116", "1 07 05 100": "117", "1 07 05 101": "118", "1 07 05 110": "120", "1 07 05 111": "121", "1 07 05 130": "122", "1 07 05 131": "123", "1 07 05 140": "124", "1 07 05 141": "125", "1 07 05 990": "126", "1 07 05 991": "127", "1 07 06 010": "132", "1 07 06 011": "133", "1 07 07 010": "139", "1 07 07 011": "140", "1 07 07 020": "141", "1 07 07 021": "142", "1 07 10 020": "152", "1 07 10 030": "153", "1 07 99 090": "147", "1 07 99 990": "148", "1 07 99 991": "149", "2 01 01 010": "168", "2 01 01 020": "169", "2 02 01 010": "179", "2 02 01 020": "180", "2 02 01 030": "181", "2 02 01 040": "182", "2 04 01 010": "193", "2 04 01 040": "194", "2 04 01 050": "195", "2 05 01 990": "200", "2 99 99 990": "205", "3 01 01 010": "213", "3 01 01 020": "214"}',
            ],
            [
                'template_name' => 'sfpo',
                'template' => '{"sample": "content"}',
            ],
            [
                'template_name' => 'sfpe',
                'template' => '{"sample": "content"}',
            ],
            [
                'template_name' => 'scf',
                'template' => '{"sample": "content"}',
            ],
            [
                'template_name' => 'sfpo_vals',
                'template' => '[17,18,19,20,21,23,27,28,32,33,34,35,36,37,46,47,48,49,53,61,62,63,64]',
            ],
            [
                'template_name' => 'sfpe_vals',
                'template' => '[14,15,16,17,22,23,24,25,26,27,35,36]',
            ],
            [
                'template_name' => 'scf_vals',
                'template' => '[13,15,16,20,21,29,33,34,35]',
            ],
            [
                'template_name' => 'sfpo_tb',
                'template' => '{"17":["1 01 01 010","1 01 01 020","1 01 02 010","1 01 02 020","1 02 01 010","1 01 03 020"],"18":["1 02 05 010"],"19":["1 03 01 010","1 03 01 070","1 03 03 010","1 03 03 030","1 03 05 020","1 03 05 030","1 03 05 040","1 03 06 010","1 03 06 020","1 03 06 990"],"20":["1 04 04 010","1 04 04 020","1 04 04 060","1 04 04 070","1 04 04 990"],"21":["1 05 01 010","1 05 01 050","1 05 01 990"],"23":["1 03 01 011"],"27":["1 03 01 010","1 03 06 020","1 03 06 990"],"28":["1 03 01 011"],"32":["1 07 04 020","1 07 04 021","1 07 04 990","1 07 04 991"],"33":["1 07 05 010","1 07 05 011","1 07 05 020","1 07 05 021","1 07 05 030","1 07 05 031","1 07 05 070","1 07 05 071","1 07 05 090","1 07 05 091","1 07 05 100","1 07 05 101","1 07 05 110","1 07 05 111","1 07 05 130","1 07 05 131","1 07 05 140","1 07 05 141","1 07 05 990","1 07 05 991"],"34":["1 07 06 010","1 07 06 011"],"35":["1 07 07 010","1 07 07 011","1 07 07 020","1 07 07 021","1 07 05 091"],"36":["1 07 99 090","1 07 99 990","1 07 99 991"],"37":["1 07 10 020","1 07 10 030"],"46":["2 01 01 010","2 01 01 020"],"47":["2 02 01 010","2 02 01 020","2 02 01 030","2 02 01 040"],"48":["2 04 01 010","2 04 01 040","2 04 01 050"],"49":["2 05 01 990"],"53":["2 99 99 990"],"61":["3 01 01 010","3 01 01 020"],"62":[["4 02 01 040","4 02 01 980","4 02 01 990","4 02 02 010","4 02 02 020","4 02 02 050","4 02 02 220","4 02 02 990","4 04 02 010","4 04 02 020","4 06 01 010"],["5 01 01 010","5 01 01 020","5 02 01 010","5 02 01 020","5 02 02 010","5 02 03 010","5 02 03 020","5 02 03 070","5 02 03 080","5 02 03 090","5 02 03 990","5 02 04 010","5 02 04 020","5 02 05 010","5 02 05 020","5 02 05 030","5 02 05 040","5 02 10 030","5 02 11 030","5 02 11 990","5 02 12 020","5 02 12 030","5 02 13 040","5 02 13 050","5 02 13 060","5 02 13 070","5 02 16 020","5 02 16 030","5 02 99 010","5 02 99 020","5 02 99 030","5 02 99 050","5 02 99 060","5 02 99 070","5 02 99 990","5 05 01 010","5 05 01 050","5 05 01 060","5 05 01 070","5 05 01 090","5 05 01 990","5 03 01 040","ckdj: Loss on Sale of Property, Plant & Equipment","5 05 03 060","5 05 04 990"],["4 03 01 020"]],"63":["ckdj: Prior Years Adjustments"],"64":["ckdj: Loss on Sale of Property, Plant and Equipment"]}',
            ],
            [
                'template_name' => 'sfpe_tb',
                'template' => '{"14":["4 02 01 040","4 02 01 980","4 02 01 990"],"15":["4 02 02 010","4 02 02 020","4 02 02 050","4 02 02 220","4 02 02 990"],"16":["4 04 02 010","4 04 02 020"],"17":["4 06 01 010"],"22":["5 01 01 010","5 01 01 020"],"23":["5 02 01 010","5 02 01 020","5 02 02 010","5 02 03 010","5 02 03 020","5 02 03 070","5 02 03 080","5 02 03 090","5 02 03 990","5 02 04 010","5 02 04 020","5 02 05 010","5 02 05 020","5 02 05 030","5 02 05 040","5 02 10 030","5 02 11 030","5 02 11 990","5 02 12 020","5 02 12 030","5 02 13 040","5 02 13 050","5 02 13 060","5 02 13 070","5 02 16 020","5 02 16 030","5 02 99 010","5 02 99 020","5 02 99 030","5 02 99 050","5 02 99 060","5 02 99 070","5 02 99 990"],"24":["5 05 01 010","5 05 01 050","5 05 01 060","5 05 01 070","5 05 01 090","5 05 01 990"],"25":["5 03 01 040"],"26":["ckdj: Loss on Sale of Property, Plant & Equipment"],"27":["5 05 03 060","5 05 04 990"],"35":["4 03 01 020"],"36":["5 03 01 040"]}',
            ],
            [
                'template_name' => 'scf_tb',
                'template' => '{"13":["4 02 01 040","4 02 01 980","4 02 01 990","4 02 02 010","4 02 02 020","4 02 02 050","4 02 02 220","4 02 02 990"],"15":["4 02 02 220"],"16":["ckdj"],"20":["ckdj"],"21":["5 01 01 010","5 01 01 020","5 01 02 010","5 01 02 020","5 01 02 030","5 01 02 040","5 01 02 050","5 01 02 060","5 01 02 080","5 01 02 100","5 01 02 110","5 01 02 120","5 01 02 130","5 01 02 140","5 01 02 150","5 01 02 990","5 01 03 010","5 01 03 020","5 01 03 030","5 01 03 040","5 01 04 030","5 01 04 990"],"29":["1 07 04 020","1 07 04 021","1 07 04 990","1 07 04 991","1 07 05 010","1 07 05 011","1 07 05 020","1 07 05 021","1 07 05 030","1 07 05 031","1 07 05 070","1 07 05 071","1 07 05 090","1 07 05 091","1 07 05 100","1 07 05 101","1 07 05 110","1 07 05 111","1 07 05 130","1 07 05 131","1 07 05 140","1 07 05 141","1 07 05 990","1 07 05 991","1 07 06 010","1 07 06 011","1 07 07 010","1 07 07 011","1 07 07 020","1 07 07 021","1 07 05 091","1 07 99 090","1 07 99 990","1 07 99 991","1 07 10 020","1 07 10 030"],"33":["ckdj"],"34":["ckdj"],"35":["ckdj"]}',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_templates');
    }
};
