<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'ABRAHAM RAMON ARIEL', 'email' => 'ramonariel@abraham.net.ar', 'active' => true, 'payment_method' => null, 'plan' => null],
            ['name' => 'ABRAHAM SUAREZ DIEGO MIGUEL', 'email' => 'migueldiegol@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'ACOSTA MARIANO SEBASTIAN', 'email' => 'mariano.983@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'ACOSTA SALCEDO LUCAS ALEJANDRO', 'email' => 'lucas241986@hotmail.com', 'active' => true, 'payment_method' => 'transfer', 'plan' => 'full', 'cents' => 1],
            ['name' => 'ALFONSO NICOLAS EZEQUIEL', 'email' => 'nicoalfonso50@hotmail.com', 'active' => false, 'payment_method' => 'transfer', 'plan' => 'full', 'cents' => 2],
            ['name' => 'ANZOATEGUI MARIANO', 'email' => 'marianz17@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'AVALOS MATIAS EXEQUIEL', 'email' => 'avalosmatias73@gmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'AVILA DAU LEONOR ALEJANDRA', 'email' => 'cyberalex_121@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'AVILA WALTER ANDRES', 'email' => 'walter_868@hotmail.com', 'active' => true, 'payment_method' => 'transfer', 'plan' => 'full', 'cents' => 3],
            ['name' => 'AYALA RUBEN DARIO', 'email' => 'rdaguitar@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'BARRAGAN EMILIANO MARTIN', 'email' => 'grintri@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'BARRENECHEA MATIAS EZEQUIEL', 'email' => 'matias_carpcn@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'BARRETO PAMELA MELANIA', 'email' => 'melani2003@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'BENTANCOR YAMIL IVAN', 'email' => 'aeroflot64@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'BERTUCCI MARTIN DARIO', 'email' => 'martin_bertucci@hotmail.com', 'active' => true, 'payment_method' => 'transfer', 'plan' => 'full', 'cents' => 4],
            ['name' => 'BLANCO JUAN MANUEL', 'email' => 'juance_34@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'BORNANCINI LEANDRO ARIEL', 'email' => 'borna_1786@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'basic'],
            ['name' => 'BOSQUE TANIA JIMENA', 'email' => 'taniajb@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'BOTOLINI MATIAS ARIEL', 'email' => 'matiasariel99@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'BRAVO CYNTHIA GABRIELA', 'email' => 'cyngb2015@gmail.com', 'active' => true, 'payment_method' => 'transfer', 'plan' => 'full', 'cents' => 5],
            ['name' => 'BUONSANTE NICOLAS GASTON', 'email' => 'n-buonsante@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'CALCAGNI LEANDRO NICOLAS', 'email' => 'lnc@live.com.ar', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'CALVI JULIO GUILLERMO', 'email' => 'malvinasargentinas82@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'CAMPOS GUILLERMO NICOLAS', 'email' => 'guillesiro@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'CARAMES PABLO NICOLAS', 'email' => 'caramesp@gmail.com', 'active' => true, 'payment_method' => 'transfer', 'plan' => 'full', 'cents' => 6],
            ['name' => 'CARBONELL CINTIA IRIS', 'email' => 'cintiairis137@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'CARDOZO ENRIQUE ARIEL', 'email' => 'enrique_513@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'CATIVA LUIS MARIANO', 'email' => 'pantera_naz@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'CHEVASCO DIAZ JUAN MANUEL', 'email' => 'ereborer@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'COHEN FERNANDO OSCAR', 'email' => 'ferco567@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'COLART JOSE LUIS', 'email' => 'joseaprea99@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'CORONATO MATIAS JAVIER', 'email' => 'coronatomatias@hotmail.com', 'active' => true, 'payment_method' => 'transfer', 'plan' => 'full', 'cents' => 7],
            ['name' => 'CRESPO MARIA ALEJANDRA', 'email' => 'alec20-02@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'CURTALE FEDERICO GERMAN', 'email' => 'fedecurta_2087@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'DE DIEGO JUAN MARTIN', 'email' => 'jmartinthor@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'DE LA IGLESIA LUIS ANGEL', 'email' => 'luisdelaiglesia@yahoo.com.ar', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'DEGAUDENCI PEDRO SEBASTIAN', 'email' => 'pdegaudenci@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'DELAYGUE TOCCHETTO ADRIAN ALEJANDRO', 'email' => 'adrian_a_delaygue@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'DI PAOLO JUAN AUGUSTO', 'email' => 'dipaolo101@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'DISTEFANO JAVIER ALEJANDRO', 'email' => 'distefanoj@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'DUARTE ANDRES EVARISTO', 'email' => 'andresevduarte@gmail.com', 'active' => true, 'payment_method' => 'transfer', 'plan' => 'full', 'cents' => 8],
            ['name' => 'ECHENIQUE OSCAR LEONARDO GASTON', 'email' => 'gastonechenique@gmail.com', 'active' => true, 'payment_method' => 'transfer', 'plan' => 'full', 'cents' => 9],
            ['name' => 'ELIZONDO OLIVERA EDUARDO ROMAN', 'email' => 'muscleroman@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'EQUIZA MARTINEZ LEONEL MATIAS', 'email' => 'matiasx_a@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'GADEA MATIAS LEANDRO NICOLAS', 'email' => 'general_matias@hotmail.com', 'active' => true, 'payment_method' => 'transfer', 'plan' => 'full', 'cents' => 10],
            ['name' => 'GARCIA MATIAS NICOLAS', 'email' => 'matigarcia_avara@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'GARCIA OVEJERO DIEGO FEDERICO', 'email' => 'overrus@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'GOMEZ MAURICIO MAXIMILIANO', 'email' => 'maumaxgo@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'GOYA ANALIA', 'email' => 'analiag@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'GUERRA AXEL MATIAS', 'email' => 'guerraaxel@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'ITURRIA JUAN', 'email' => 'iturria_ju@hotmail.com', 'active' => true, 'payment_method' => 'transfer', 'plan' => 'full', 'cents' => 11],
            ['name' => 'LENARDUZZI CHRISTIAN JOSE', 'email' => 'cjlenarduzzi@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'LOTO GABRIELA KARINA', 'email' => 'gabrielaloto@yahoo.com.ar', 'active' => true, 'payment_method' => 'transfer', 'plan' => 'full', 'cents' => 12],
            ['name' => 'LUTRI FACUNDO NAHUEL', 'email' => 'facu2004segundos@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'MARTIN SILVA RENZO DAVID', 'email' => 'zenzodbs@yahoo.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'MARTINEZ DORA VANINA', 'email' => 'vaninamartinez22@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'MARTINEZ REUMANN HERNAN', 'email' => 'hmreumann@hotmail.com', 'password' => Hash::make('password'), 'active' => true, 'payment_method' => 'transfer', 'plan' => 'full', 'cents' => 13],
            ['name' => 'MENCHACA EZEQUIEL HUMBERTO', 'email' => 'ezekys2000@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'MIGUEL MARTIN IGNACIO', 'email' => 'alan_mclaurin_taylor@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'MOLINA JOSE ALBERTO', 'email' => 'contactoprivado01@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'MONDINE EMMANUEL LEANDRO', 'email' => 'elmondy_7@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'MURGIA LUCAS DAVID', 'email' => 'reypatriciolucia22@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'NICOLAU JUAN', 'email' => 'jn_8@hotmail.com', 'active' => true, 'payment_method' => 'transfer', 'plan' => 'full', 'cents' => 14],
            ['name' => 'NIETO RODRIGO ANDRES', 'email' => 'chango_ran04@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'NIGRO MAXIMILIANO ARIEL', 'email' => 'maximil85@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'NIMIS DANIELA PAOLA', 'email' => 'danielapaolanimis@gmail.com', 'active' => true, 'payment_method' => 'transfer', 'plan' => 'full', 'cents' => 15],
            ['name' => 'NOLASCO DANIEL FELIPE', 'email' => 'danieldfn@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'NOTTI PAULA ALEJANDRA', 'email' => 'pnotti19@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'OVEJERO HERNAN JORGE', 'email' => 'hernan_roses@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'PAZ RODRIGO JAVIER', 'email' => 'Rodrigojavierp7@gmail.com', 'active' => true, 'payment_method' => 'transfer', 'plan' => 'full', 'cents' => 17],
            ['name' => 'PEREYRA FACUNDO NICOLAS', 'email' => 'pereyrafn@hotmail.com', 'active' => true, 'payment_method' => 'transfer', 'plan' => 'full', 'cents' => 16],
            ['name' => 'PEREZ FEDERICO IGNACIO', 'email' => 'kriegmachinery@gmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'PICO CYNTIA RITA', 'email' => 'pico487@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'PISA FRANCO DANIEL', 'email' => 'pisa_fran@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'PROST UBALDO', 'email' => 'ubiprost@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'RIFE CABOS RODRIGO', 'email' => 'rodrigorife@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'RIOS PABLO DAMIAN', 'email' => 'pablodamianrios@hotmail.es', 'active' => true, 'payment_method' => 'transfer', 'plan' => 'full', 'cents' => 18],
            ['name' => 'RODRIGUEZ DAMIAN ALBERTO', 'email' => 'damro86_09@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'RODRIGUEZ DUC FRANCISCO JOAQUIN', 'email' => 'duc_cn@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'ROJAS MARCELO JOSE DANIEL', 'email' => 'marcelojosedanielrojas@gmail.com', 'active' => true, 'payment_method' => 'transfer', 'plan' => 'full', 'cents' => 19],
            ['name' => 'ROMERO JUAN MANUEL', 'email' => 'juanmaromero23@live.com.ar', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'ROMERO PABLO ALEJANDRO', 'email' => 'alitobahia128@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'RUHL EMANUEL LEANDRO', 'email' => 'emaruhl@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'SANCHEZ ESCOBAR FERNANDO FAVIAN', 'email' => 'fava_8769@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'SCARABOTTI CESAR FEDERICO', 'email' => 'cesarxeneize@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'SCHUTZ CARLOS CHRISTIAN', 'email' => 'christian-schutz@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'SOSA MARCELO ALEJANDRO', 'email' => 'marcelososa22@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'SUAREZ NICOLAS ALFREDO', 'email' => 'nic_suarez255@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'TOLEDANO LORENA PAOLA', 'email' => 'lore_toledano@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'TOULEMONDE MATIAS', 'email' => 'mati_tule@hotmail.com', 'active' => true, 'payment_method' => 'transfer', 'plan' => 'full', 'cents' => 20],
            ['name' => 'URQUIZA GABRIEL ALEJANDRO', 'email' => 'gabriel_urquiza@hotmail.com.ar', 'active' => true, 'payment_method' => 'transfer', 'plan' => 'full', 'cents' => 21],
            ['name' => 'URRICELQUI IGNACIO JAVIER', 'email' => 'ignacio_nacho85@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'VASQUEZ WALTER MATIAS', 'email' => 'mattu095@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'VIANA JUAN GABRIEL', 'email' => 'gabrielviana_mdq@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'YEMHA FACUNDO AHMED', 'email' => 'facundoayemha@hotmail.com', 'active' => true, 'payment_method' => 'siaf', 'plan' => 'full'],
            ['name' => 'YURQUINA ANDRES FROILAN', 'email' => 'andres_7714@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'ZAPATA FERNANDO', 'email' => 'fer_zapata_82@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'ZUNDA MEOQUI ADRIAN', 'email' => 'adrianzunda@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
            ['name' => 'ZURITA GONZALO RAMON', 'email' => 'grz_84@hotmail.com', 'active' => false, 'payment_method' => null, 'plan' => null],
        ];

        foreach ($users as $userData) {
            $userData['password'] = 'promocion138';
            User::create($userData);
        }
    }
}
