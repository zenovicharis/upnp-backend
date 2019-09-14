<?php


use Phinx\Seed\AbstractSeed;

class NewsSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            [
                "title" => "i ja sam izvidjac",
                "content" => "Udruženje psihologa Novi Pazar je na poziv Odreda izviđača Sandžak organizovalo akciju \"I ja sam izviđač\" u kojoj je učestvovalo 30 dece - korisnika udruženja. Cilj ove akcije je da se deca sa smetnjama u razvoju upoznaju sa članovima ove prestižne novopazarske organizacije, kao i osnovnim veštinama izviđača. Današnja aktivnost koja se održala na rekreacionom centru omogućila je korisnicima da slobodno vreme provedu na kvalitetan način uz boravak na čistom vazduhu što povoljno utiče na njihovo zdravstveno stanje. A što je još važnije, deca su imala priliku da družeći se sa vršnjacima - izviđačima unaprede svoje socijalne veštine.",
                "category" => "blog",
                "language" => "serbian",
                "image_id" => 1
            ],
            [
                "title" => "inkluzivna akademija",
                "content" => "U okviru programa

„E2E – Znanjem do posla“ koje finansira Švajcarska Agencija za Razvoj i Saradnju - SDC kao donator, koji u Srbiji sprovodi IP CONSULT,

Otpočele su aktivnosti projekta INKLUZIVNA AKADEMIJA koji sprovodi Udruženje psihologa Novi Pazar sa partnerima : Kancelarija za mlade Novi Pazar,  Centar za socijalni rad Novi Pazar i Nacionalna služba za zapošljavanje – filijala Novi Pazar.

Projekat je namenjen mladima od 15 do 35 godina sa smetnjama u razvoju i/ili invaliditetom

Projekat ima za cilj  pomoći mladima sa invaliditetom/smetnjama u razvoju da steknu neophodne veštine i znanje da se uključe u proces rada i steknu praksu za određena zanimanja u skladu sa njihovim mogućnostima i preferencijama. Trajanje programa 12 meseci. Broj mesta ograničen.

Program INKLUZIVNA AKADEMIJA obuhvata:

Profesionalna orijentacija za odabrane učesnike
Procena tržišta rada i prilagođavanje profesionalne orijentacije učesnika
 OBUKA u trajanju od 6 meseci, koja obuhvata kurseve :

Lične kompetencije,
Profesionalne kompetencije,
Sticanje praktičnih veština (obuka za rad na računaru)",
                "category" => "blog",
                "language" => "english",
                "image_id" => 1
            ],
            [
                "title" => "i ja sam izvidjac",
                "content" => "Udruženje psihologa Novi Pazar je na poziv Odreda izviđača Sandžak organizovalo akciju \"I ja sam izviđač\" u kojoj je učestvovalo 30 dece - korisnika udruženja. Cilj ove akcije je da se deca sa smetnjama u razvoju upoznaju sa članovima ove prestižne novopazarske organizacije, kao i osnovnim veštinama izviđača. Današnja aktivnost koja se održala na rekreacionom centru omogućila je korisnicima da slobodno vreme provedu na kvalitetan način uz boravak na čistom vazduhu što povoljno utiče na njihovo zdravstveno stanje. A što je još važnije, deca su imala priliku da družeći se sa vršnjacima - izviđačima unaprede svoje socijalne veštine.",
                "category" => "projekt",
                "language" => "serbian",
                "image_id" => 1
            ]

        ];
        $user = $this->table('news');
        $user->insert($data)
            ->save();
    }
}
