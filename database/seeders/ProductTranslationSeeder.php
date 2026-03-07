<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductTranslationSeeder extends Seeder
{
    public function run(): void
    {
        $translations = [
            // PRODUITS EXISTANTS
            'Croquettes Royal Canin Maxi Adult' => [
                'fr' => [
                    'name' => 'Croquettes Royal Canin Maxi Adult',
                    'description' => 'Alimentation complète pour chiens adultes de grande taille (26-44kg). Riche en protéines et vitamines.',
                    'short_description' => 'Croquettes premium pour grands chiens',
                ],
                'en' => [
                    'name' => 'Royal Canin Maxi Adult Kibble',
                    'description' => 'Complete food for adult dogs of large breeds (26-44kg). Rich in proteins and vitamins.',
                    'short_description' => 'Premium kibble for large dogs',
                ],
                'pt_BR' => [
                    'name' => 'Ração Royal Canin Maxi Adult',
                    'description' => 'Alimento completo para cães adultos de raças grandes (26-44kg). Rico em proteínas e vitaminas.',
                    'short_description' => 'Ração premium para cães grandes',
                ],
                'es' => [
                    'name' => 'Crocquetas Royal Canin Maxi Adult',
                    'description' => 'Alimento completo para perros adultos de razas grandes (26-44kg). Rico en proteínas y vitaminas.',
                    'short_description' => 'Crocquetas premium para perros grandes',
                ],
                'de' => [
                    'name' => 'Royal Canin Maxi Adult Trockenfutter',
                    'description' => 'Vollständiges Futter für erwachsene Hunde großer Rassen (26-44kg). Reich an Proteinen und Vitaminen.',
                    'short_description' => 'Premium-Trockenfutter für große Hunde',
                ],
                'it' => [
                    'name' => 'Royal Canin Maxi Adult Crocchette',
                    'description' => 'Alimento completo per cani adulti di razze grandi (26-44kg). Ricco di proteine e vitamine.',
                    'short_description' => 'Crocchette premium per cani grandi',
                ],
            ],
            
            'Croquettes Purina Pro Plan Sensitive' => [
                'fr' => [
                    'name' => 'Croquettes Purina Pro Plan Sensitive',
                    'description' => 'Pour chiens à peau sensible. Formule avec saumon et riz.',
                    'short_description' => 'Croquettes pour peaux sensibles',
                ],
                'en' => [
                    'name' => 'Purina Pro Plan Sensitive Kibble',
                    'description' => 'For dogs with sensitive skin. Formula with salmon and rice.',
                    'short_description' => 'Kibble for sensitive skin',
                ],
                'pt_BR' => [
                    'name' => 'Ração Purina Pro Plan Sensitive',
                    'description' => 'Para cães com pele sensível. Fórmula com salmão e arroz.',
                    'short_description' => 'Ração para pele sensível',
                ],
                'es' => [
                    'name' => 'Crocquetas Purina Pro Plan Sensitive',
                    'description' => 'Para perros con piel sensible. Fórmula con salmón y arroz.',
                    'short_description' => 'Crocquetas para piel sensible',
                ],
                'de' => [
                    'name' => 'Purina Pro Plan Sensitive Trockenfutter',
                    'description' => 'Für Hunde mit empfindlicher Haut. Formel mit Lachs und Reis.',
                    'short_description' => 'Trockenfutter für empfindliche Haut',
                ],
                'it' => [
                    'name' => 'Purina Pro Plan Sensitive Crocchette',
                    'description' => 'Per cani con pelle sensibile. Formula con salmone e riso.',
                    'short_description' => 'Crocchette per pelle sensibile',
                ],
            ],
            
            'Gamelle anti-glouton' => [
                'fr' => [
                    'name' => 'Gamelle anti-glouton',
                    'description' => 'Gamelle avec labyrinthe pour ralentir la prise de nourriture.',
                    'short_description' => 'Gamelle anti-glouton',
                ],
                'en' => [
                    'name' => 'Slow Feeder Dog Bowl',
                    'description' => 'Bowl with labyrinth to slow down food intake.',
                    'short_description' => 'Slow feeder bowl',
                ],
                'pt_BR' => [
                    'name' => 'Tigela anti-glutonaria',
                    'description' => 'Tigela com labirinto para retardar a ingestão de alimentos.',
                    'short_description' => 'Tigela anti-glutonaria',
                ],
                'es' => [
                    'name' => 'Cuenco anti-glotonería',
                    'description' => 'Cuenco con laberinto para ralentizar la ingesta de alimentos.',
                    'short_description' => 'Cuenco lento',
                ],
                'de' => [
                    'name' => 'Anti-Schlingnapf',
                    'description' => 'Napf mit Labyrinth, um die Nahrungsaufnahme zu verlangsamen.',
                    'short_description' => 'Langsamfutternapf',
                ],
                'it' => [
                    'name' => 'Ciotola anti-ingozzamento',
                    'description' => 'Ciotola con labirinto per rallentare l\'assunzione di cibo.',
                    'short_description' => 'Ciotola lenta',
                ],
            ],
            
            'Nourriture pour poissons' => [
                'fr' => [
                    'name' => 'Nourriture pour poissons',
                    'description' => 'Alimentation équilibrée pour poissons d\'aquarium',
                    'short_description' => 'Nourriture aquarium',
                ],
                'en' => [
                    'name' => 'Fish Food',
                    'description' => 'Balanced nutrition for aquarium fish',
                    'short_description' => 'Fish food',
                ],
                'pt_BR' => [
                    'name' => 'Alimento para peixes',
                    'description' => 'Alimentação balanceada para peixes de aquário',
                    'short_description' => 'Alimento para peixes',
                ],
                'es' => [
                    'name' => 'Comida para peces',
                    'description' => 'Alimentación equilibrada para peces de acuario',
                    'short_description' => 'Comida para peces',
                ],
                'de' => [
                    'name' => 'Fischfutter',
                    'description' => 'Ausgewogene Ernährung für Aquarienfische',
                    'short_description' => 'Fischfutter',
                ],
                'it' => [
                    'name' => 'Cibo per pesci',
                    'description' => 'Alimentazione equilibrata per pesci d\'acquario',
                    'short_description' => 'Cibo per pesci',
                ],
            ],
            
            'Aquarium 100L' => [
                'fr' => [
                    'name' => 'Aquarium 100L',
                    'description' => 'Aquarium complet pour poissons',
                    'short_description' => 'Aquarium 100L',
                ],
                'en' => [
                    'name' => '100L Aquarium',
                    'description' => 'Complete aquarium for fish',
                    'short_description' => '100L aquarium',
                ],
                'pt_BR' => [
                    'name' => 'Aquário 100L',
                    'description' => 'Aquário completo para peixes',
                    'short_description' => 'Aquário 100L',
                ],
                'es' => [
                    'name' => 'Acuario 100L',
                    'description' => 'Acuario completo para peces',
                    'short_description' => 'Acuario 100L',
                ],
                'de' => [
                    'name' => 'Aquarium 100L',
                    'description' => 'Komplettes Aquarium für Fische',
                    'short_description' => '100L Aquarium',
                ],
                'it' => [
                    'name' => 'Acquario 100L',
                    'description' => 'Acquario completo per pesci',
                    'short_description' => 'Acquario 100L',
                ],
            ],
            
            'Croquettes Royal Canin Sterilised' => [
                'fr' => [
                    'name' => 'Croquettes Royal Canin Sterilised',
                    'description' => 'Alimentation pour chats stérilisés',
                    'short_description' => 'Croquettes pour chats stérilisés',
                ],
                'en' => [
                    'name' => 'Royal Canin Sterilised Cat Food',
                    'description' => 'Food for sterilized cats',
                    'short_description' => 'Sterilised cat food',
                ],
                'pt_BR' => [
                    'name' => 'Ração Royal Canin Sterilised',
                    'description' => 'Alimento para gatos esterilizados',
                    'short_description' => 'Ração para gatos esterilizados',
                ],
                'es' => [
                    'name' => 'Crocquetas Royal Canin Sterilised',
                    'description' => 'Alimento para gatos esterilizados',
                    'short_description' => 'Crocquetas para gatos esterilizados',
                ],
                'de' => [
                    'name' => 'Royal Canin Sterilised Katzenfutter',
                    'description' => 'Futter für sterilisierte Katzen',
                    'short_description' => 'Katzenfutter für sterilisierte',
                ],
                'it' => [
                    'name' => 'Royal Canin Sterilised Cibo per gatti',
                    'description' => 'Cibo per gatti sterilizzati',
                    'short_description' => 'Cibo per gatti sterilizzati',
                ],
            ],
            
            'Pâtée Whiskas en gelée' => [
                'fr' => [
                    'name' => 'Pâtée Whiskas en gelée',
                    'description' => 'Alimentation humide savoureuse pour chats en gelée',
                    'short_description' => 'Pâtée pour chats',
                ],
                'en' => [
                    'name' => 'Whiskas Jelly Cat Food',
                    'description' => 'Tasty wet cat food in jelly',
                    'short_description' => 'Cat food in jelly',
                ],
                'pt_BR' => [
                    'name' => 'Patê Whiskas em geleia',
                    'description' => 'Alimento úmido saboroso para gatos em geleia',
                    'short_description' => 'Patê em geleia',
                ],
                'es' => [
                    'name' => 'Comida húmeda Whiskas en gelatina',
                    'description' => 'Sabrosa comida húmeda para gatos en gelatina',
                    'short_description' => 'Comida en gelatina',
                ],
                'de' => [
                    'name' => 'Whiskas Nassfutter in Gelee',
                    'description' => 'Leckeres Nassfutter für Katzen in Gelee',
                    'short_description' => 'Nassfutter in Gelee',
                ],
                'it' => [
                    'name' => 'Whiskas Cibo umido in gelatina',
                    'description' => 'Cibo umido gustoso per gatti in gelatina',
                    'short_description' => 'Cibo in gelatina',
                ],
            ],
            
            'Antiparasitaire Frontline Combo' => [
                'fr' => [
                    'name' => 'Antiparasitaire Frontline Combo',
                    'description' => 'Traitement efficace contre les puces et tiques pour chiens et chats',
                    'short_description' => 'Antiparasitaire',
                ],
                'en' => [
                    'name' => 'Frontline Combo Flea Treatment',
                    'description' => 'Effective flea and tick treatment for dogs and cats',
                    'short_description' => 'Flea treatment',
                ],
                'pt_BR' => [
                    'name' => 'Antiparasitário Frontline Combo',
                    'description' => 'Tratamento eficaz contra pulgas e carrapatos para cães e gatos',
                    'short_description' => 'Antiparasitário',
                ],
                'es' => [
                    'name' => 'Antiparasitario Frontline Combo',
                    'description' => 'Tratamiento eficaz contra pulgas y garrapatas para perros y gatos',
                    'short_description' => 'Antiparasitario',
                ],
                'de' => [
                    'name' => 'Frontline Combo Antiparasitikum',
                    'description' => 'Wirksame Zecken- und Flohbehandlung für Hunde und Katzen',
                    'short_description' => 'Antiparasitikum',
                ],
                'it' => [
                    'name' => 'Frontline Combo Antiparassitario',
                    'description' => 'Trattamento efficace contro pulci e zecche per cani e gatti',
                    'short_description' => 'Antiparassitario',
                ],
            ],
            
            'Litière agglomérante Catsan' => [
                'fr' => [
                    'name' => 'Litière agglomérante Catsan',
                    'description' => 'Litière pour chat de haute qualité pour un excellent contrôle des odeurs',
                    'short_description' => 'Litière agglomérante',
                ],
                'en' => [
                    'name' => 'Catsan Clumping Litter',
                    'description' => 'High-quality clumping cat litter for excellent odor control',
                    'short_description' => 'Clumping litter',
                ],
                'pt_BR' => [
                    'name' => 'Areia aglomerante Catsan',
                    'description' => 'Areia de gato aglomerante de alta qualidade para excelente controle de odores',
                    'short_description' => 'Areia aglomerante',
                ],
                'es' => [
                    'name' => 'Arena aglomerante Catsan',
                    'description' => 'Arena para gatos aglomerante de alta calidad para un excelente control de olores',
                    'short_description' => 'Arena aglomerante',
                ],
                'de' => [
                    'name' => 'Catsan Klumpstreu',
                    'description' => 'Hochwertige Klumpstreu für Katzen mit ausgezeichneter Geruchskontrolle',
                    'short_description' => 'Klumpstreu',
                ],
                'it' => [
                    'name' => 'Catsan Lettiera agglomerante',
                    'description' => 'Lettiera agglomerante di alta qualità per gatti per un eccellente controllo degli odori',
                    'short_description' => 'Lettiera agglomerante',
                ],
            ],
            
            // PRODUITS MANQUANTS DE LA BDD
            'gamelle de chien' => [  // ID 2
                'fr' => [
                    'name' => 'gamelle de chien',
                    'description' => 'Gamelle robuste en acier inoxydable pour chien, antidérapante et facile à nettoyer.',
                    'short_description' => 'Gamelle pour chien',
                ],
                'en' => [
                    'name' => 'Dog Bowl',
                    'description' => 'Sturdy stainless steel dog bowl, non-slip and easy to clean.',
                    'short_description' => 'Dog bowl',
                ],
                'pt_BR' => [
                    'name' => 'Tigela para cachorro',
                    'description' => 'Tigela resistente em aço inoxidável para cães, antiderrapante e fácil de limpar.',
                    'short_description' => 'Tigela para cachorro',
                ],
                'es' => [
                    'name' => 'Cuenco para perro',
                    'description' => 'Cuenco resistente de acero inoxidable para perros, antideslizante y fácil de limpiar.',
                    'short_description' => 'Cuenco para perro',
                ],
                'de' => [
                    'name' => 'Hundenapf',
                    'description' => 'Robuster Edelstahl-Hundenapf, rutschfest und leicht zu reinigen.',
                    'short_description' => 'Hundenapf',
                ],
                'it' => [
                    'name' => 'Ciotola per cani',
                    'description' => 'Ciotola robusta in acciaio inox per cani, antiscivolo e facile da pulire.',
                    'short_description' => 'Ciotola per cani',
                ],
            ],
            
            'Lavabo' => [  // ID 3
                'fr' => [
                    'name' => 'Lavabo',
                    'description' => 'Lavabo en céramique blanche avec robinetterie comprise.',
                    'short_description' => 'Lavabo salle de bain',
                ],
                'en' => [
                    'name' => 'Sink',
                    'description' => 'White ceramic sink with faucet included.',
                    'short_description' => 'Bathroom sink',
                ],
                'pt_BR' => [
                    'name' => 'Lavatório',
                    'description' => 'Lavatório de cerâmica branca com torneira inclusa.',
                    'short_description' => 'Lavatório banheiro',
                ],
                'es' => [
                    'name' => 'Lavabo',
                    'description' => 'Lavabo de cerámica blanca con grifería incluida.',
                    'short_description' => 'Lavabo baño',
                ],
                'de' => [
                    'name' => 'Waschbecken',
                    'description' => 'Weißes Keramikwaschbecken mit Wasserhahn.',
                    'short_description' => 'Badezimmerwaschbecken',
                ],
                'it' => [
                    'name' => 'Lavabo',
                    'description' => 'Lavabo in ceramica bianca con rubinetteria inclusa.',
                    'short_description' => 'Lavabo bagno',
                ],
            ],
            
            'Croquette pour chien' => [  // ID 4
                'fr' => [
                    'name' => 'Croquette pour chien',
                    'description' => 'Alimentation sèche premium pour chien, riche en nutriments essentiels.',
                    'short_description' => 'Croquettes chien',
                ],
                'en' => [
                    'name' => 'Dog Kibble',
                    'description' => 'Premium dry dog food, rich in essential nutrients.',
                    'short_description' => 'Dog food',
                ],
                'pt_BR' => [
                    'name' => 'Ração para cachorro',
                    'description' => 'Alimento seco premium para cães, rico em nutrientes essenciais.',
                    'short_description' => 'Ração para cachorro',
                ],
                'es' => [
                    'name' => 'Croqueta para perro',
                    'description' => 'Alimento seco premium para perros, rico en nutrientes esenciales.',
                    'short_description' => 'Comida para perro',
                ],
                'de' => [
                    'name' => 'Hundetrockenfutter',
                    'description' => 'Premium-Trockenfutter für Hunde, reich an essentiellen Nährstoffen.',
                    'short_description' => 'Hundefutter',
                ],
                'it' => [
                    'name' => 'Cibo secco per cani',
                    'description' => 'Cibo secco premium per cani, ricco di nutrienti essenziali.',
                    'short_description' => 'Cibo per cani',
                ],
            ],
            
            'Friandises Pedigree Dentastix' => [  // ID 7
                'fr' => [
                    'name' => 'Friandises Pedigree Dentastix',
                    'description' => 'Bâtonnets dentaires pour chiens, aident à réduire la formation de tartre.',
                    'short_description' => 'Friandises dentaires',
                ],
                'en' => [
                    'name' => 'Pedigree Dentastix Treats',
                    'description' => 'Dental sticks for dogs, help reduce tartar buildup.',
                    'short_description' => 'Dental treats',
                ],
                'pt_BR' => [
                    'name' => 'Petiscos Pedigree Dentastix',
                    'description' => 'Bastões dentais para cães, ajudam a reduzir a formação de tártaro.',
                    'short_description' => 'Petiscos dentais',
                ],
                'es' => [
                    'name' => 'Golosinas Pedigree Dentastix',
                    'description' => 'Barras dentales para perros, ayudan a reducir la formación de sarro.',
                    'short_description' => 'Golosinas dentales',
                ],
                'de' => [
                    'name' => 'Pedigree Dentastix Leckerlis',
                    'description' => 'Zahnpflege-Sticks für Hunde, helfen bei der Reduzierung von Zahnstein.',
                    'short_description' => 'Zahnpflege-Leckerlis',
                ],
                'it' => [
                    'name' => 'Pedigree Dentastix Snack',
                    'description' => 'Bastoncini dentali per cani, aiutano a ridurre la formazione di tartaro.',
                    'short_description' => 'Snack dentali',
                ],
            ],
            
            'Arbre à chat 3 étages' => [  // ID 12
                'fr' => [
                    'name' => 'Arbre à chat 3 étages',
                    'description' => 'Arbre à chat avec 3 niveaux, griffoirs et cachettes pour le bien-être de votre chat.',
                    'short_description' => 'Arbre à chat',
                ],
                'en' => [
                    'name' => '3-Tier Cat Tree',
                    'description' => 'Cat tree with 3 levels, scratching posts and hiding spots for your cat\'s well-being.',
                    'short_description' => 'Cat tree',
                ],
                'pt_BR' => [
                    'name' => 'Árvore para gatos 3 andares',
                    'description' => 'Árvore para gatos com 3 níveis, arranhadores e esconderijos para o bem-estar do seu gato.',
                    'short_description' => 'Árvore para gatos',
                ],
                'es' => [
                    'name' => 'Árbol para gatos 3 niveles',
                    'description' => 'Árbol para gatos con 3 niveles, rascadores y escondites para el bienestar de tu gato.',
                    'short_description' => 'Árbol para gatos',
                ],
                'de' => [
                    'name' => '3-stöckiger Kratzbaum',
                    'description' => 'Kratzbaum mit 3 Ebenen, Kratzsäulen und Verstecken für das Wohlbefinden Ihrer Katze.',
                    'short_description' => 'Kratzbaum',
                ],
                'it' => [
                    'name' => 'Tiragraffi per gatti 3 livelli',
                    'description' => 'Tiragraffi per gatti con 3 livelli, tiragraffi e nascondigli per il benessere del tuo gatto.',
                    'short_description' => 'Tiragraffi per gatti',
                ],
            ],
            
            'Vermifuge Milbemax pour chiens' => [  // ID 14
                'fr' => [
                    'name' => 'Vermifuge Milbemax pour chiens',
                    'description' => 'Traitement vermifuge efficace contre les vers intestinaux pour chiens.',
                    'short_description' => 'Vermifuge chien',
                ],
                'en' => [
                    'name' => 'Milbemax Dewormer for Dogs',
                    'description' => 'Effective deworming treatment against intestinal worms for dogs.',
                    'short_description' => 'Dog dewormer',
                ],
                'pt_BR' => [
                    'name' => 'Vermífugo Milbemax para cães',
                    'description' => 'Tratamento vermífugo eficaz contra vermes intestinais para cães.',
                    'short_description' => 'Vermífugo para cães',
                ],
                'es' => [
                    'name' => 'Desparasitante Milbemax para perros',
                    'description' => 'Tratamiento desparasitante eficaz contra lombrices intestinales para perros.',
                    'short_description' => 'Desparasitante para perros',
                ],
                'de' => [
                    'name' => 'Milbemax Wurmkur für Hunde',
                    'description' => 'Wirksame Wurmkur gegen Darmwürmer für Hunde.',
                    'short_description' => 'Wurmkur für Hunde',
                ],
                'it' => [
                    'name' => 'Sverminante Milbemax per cani',
                    'description' => 'Trattamento sverminante efficace contro i vermi intestinali per cani.',
                    'short_description' => 'Sverminante per cani',
                ],
            ],
            
            'Armoire en bois' => [  // ID 21
                'fr' => [
                    'name' => 'Armoire en bois',
                    'description' => 'Armoire de rangement en bois massif avec étagères ajustables.',
                    'short_description' => 'Armoire en bois',
                ],
                'en' => [
                    'name' => 'Wooden Cabinet',
                    'description' => 'Solid wood storage cabinet with adjustable shelves.',
                    'short_description' => 'Wooden cabinet',
                ],
                'pt_BR' => [
                    'name' => 'Armário de madeira',
                    'description' => 'Armário de armazenamento em madeira maciça com prateleiras ajustáveis.',
                    'short_description' => 'Armário de madeira',
                ],
                'es' => [
                    'name' => 'Armario de madera',
                    'description' => 'Armario de almacenaje de madera maciza con estantes ajustables.',
                    'short_description' => 'Armario de madera',
                ],
                'de' => [
                    'name' => 'Holzschrank',
                    'description' => 'Massiver Holzschrank mit verstellbaren Regalen.',
                    'short_description' => 'Holzschrank',
                ],
                'it' => [
                    'name' => 'Armadio in legno',
                    'description' => 'Armadio portaoggetti in legno massello con ripiani regolabili.',
                    'short_description' => 'Armadio in legno',
                ],
            ],
            
            // VINS DE BOURGOGNE (déjà dans votre fichier)
            'Bourgogne Rouge 2024 - "Les Champs d\'Argent"' => [
                'fr' => [
                    'name' => 'Bourgogne Rouge 2024 - "Les Champs d\'Argent"',
                    'description' => 'Cuvée très spécifique, ce Bourgogne est issu de vieilles vignes plantées en 1952.',
                    'short_description' => 'Vin rouge de Bourgogne',
                ],
                'en' => [
                    'name' => 'Burgundy Red 2024 - "The Silver Fields"',
                    'description' => 'Very specific vintage, this Burgundy comes from old vines planted in 1952.',
                    'short_description' => 'Red wine from Burgundy',
                ],
                'pt_BR' => [
                    'name' => 'Borgonha Tinto 2024 - "Os Campos de Prata"',
                    'description' => 'Cuvée muito específica, este Borgonha vem de vinhas velhas plantadas em 1952.',
                    'short_description' => 'Vinho tinto da Borgonha',
                ],
                'es' => [
                    'name' => 'Borgoña Tinto 2024 - "Los Campos de Plata"',
                    'description' => 'Cuvée muy específica, este Borgoña proviene de viñas viejas plantadas en 1952.',
                    'short_description' => 'Vino tinto de Borgoña',
                ],
                'de' => [
                    'name' => 'Burgunder Rot 2024 - "Die Silberfelder"',
                    'description' => 'Sehr spezifischer Jahrgang, dieser Burgunder stammt von alten Reben aus dem Jahr 1952.',
                    'short_description' => 'Rotwein aus Burgund',
                ],
                'it' => [
                    'name' => 'Borgogna Rosso 2024 - "I Campi d\'Argento"',
                    'description' => 'Cuvée molto specifica, questa Borgogna proviene da vecchie viti piantate nel 1952.',
                    'short_description' => 'Vino rosso di Borgogna',
                ],
            ],
            
            'Bourgogne Passetoutgrain 2022' => [
                'fr' => [
                    'name' => 'Bourgogne Passetoutgrain 2022',
                    'description' => 'Le célèbre Bourgogne Passetoutgrains, assemblage de Pinot Noir et Gamay.',
                    'short_description' => 'Vin rouge de Bourgogne',
                ],
                'en' => [
                    'name' => 'Burgundy Passetoutgrain 2022',
                    'description' => 'The famous Burgundy Passetoutgrains, blend of Pinot Noir and Gamay.',
                    'short_description' => 'Red wine from Burgundy',
                ],
                'pt_BR' => [
                    'name' => 'Borgonha Passetoutgrain 2022',
                    'description' => 'O famoso Borgonha Passetoutgrains, assemblage de Pinot Noir e Gamay.',
                    'short_description' => 'Vinho tinto da Borgonha',
                ],
                'es' => [
                    'name' => 'Borgoña Passetoutgrain 2022',
                    'description' => 'El famoso Borgoña Passetoutgrains, mezcla de Pinot Noir y Gamay.',
                    'short_description' => 'Vino tinto de Borgoña',
                ],
                'de' => [
                    'name' => 'Burgunder Passetoutgrain 2022',
                    'description' => 'Der berühmte Burgunder Passetoutgrains, Cuvée aus Pinot Noir und Gamay.',
                    'short_description' => 'Rotwein aus Burgund',
                ],
                'it' => [
                    'name' => 'Borgogna Passetoutgrain 2022',
                    'description' => 'La famosa Borgogna Passetoutgrains, blend di Pinot Noir e Gamay.',
                    'short_description' => 'Vino rosso di Borgogna',
                ],
            ],
            
            'Couteaux Bourguignons 2021' => [
                'fr' => [
                    'name' => 'Couteaux Bourguignons 2021',
                    'description' => 'Coteaux Bourguignons 2021 réalisés par le domaine Remoriquet.',
                    'short_description' => 'Vin rouge de Bourgogne',
                ],
                'en' => [
                    'name' => 'Burgundy Knives 2021',
                    'description' => 'Coteaux Bourguignons 2021 produced by the Remoriquet estate.',
                    'short_description' => 'Red wine from Burgundy',
                ],
                'pt_BR' => [
                    'name' => 'Facas da Borgonha 2021',
                    'description' => 'Coteaux Bourguignons 2021 produzidos pelo domínio Remoriquet.',
                    'short_description' => 'Vinho tinto da Borgonha',
                ],
                'es' => [
                    'name' => 'Cuchillos de Borgoña 2021',
                    'description' => 'Coteaux Bourguignons 2021 producidos por la finca Remoriquet.',
                    'short_description' => 'Vino tinto de Borgoña',
                ],
                'de' => [
                    'name' => 'Burgunder Messer 2021',
                    'description' => 'Coteaux Bourguignons 2021 hergestellt vom Weingut Remoriquet.',
                    'short_description' => 'Rotwein aus Burgund',
                ],
                'it' => [
                    'name' => 'Coltelli di Borgogna 2021',
                    'description' => 'Coteaux Bourguignons 2021 prodotti dalla tenuta Remoriquet.',
                    'short_description' => 'Vino rosso di Borgogna',
                ],
            ],
            
            'Hautes côtes de nuit' => [
                'fr' => [
                    'name' => 'Hautes côtes de nuit',
                    'description' => 'Vin rouge domaine du baccara aux tannins soyeux.',
                    'short_description' => 'Vin rouge de Bourgogne',
                ],
                'en' => [
                    'name' => 'High Coasts of Night',
                    'description' => 'Red wine from baccara estate with silky tannins.',
                    'short_description' => 'Red wine from Burgundy',
                ],
                'pt_BR' => [
                    'name' => 'Altas encostas da noite',
                    'description' => 'Vinho tinto do domínio baccara com taninos sedosos.',
                    'short_description' => 'Vinho tinto da Borgonha',
                ],
                'es' => [
                    'name' => 'Altas costas de la noche',
                    'description' => 'Vino tinto de la finca baccara con taninos sedosos.',
                    'short_description' => 'Vino tinto de Borgoña',
                ],
                'de' => [
                    'name' => 'Hohe Küsten der Nacht',
                    'description' => 'Rotwein vom Weingut Baccara mit seidigen Tanninen.',
                    'short_description' => 'Rotwein aus Burgund',
                ],
                'it' => [
                    'name' => 'Alte coste della notte',
                    'description' => 'Vino rosso della tenuta baccara con tannini setosi.',
                    'short_description' => 'Vino rosso di Borgogna',
                ],
            ],
            
            'Mercurey 2022 Rouge - "Les Chavances"' => [
                'fr' => [
                    'name' => 'Mercurey 2022 Rouge - "Les Chavances"',
                    'description' => 'Vin rouge de Mercurey aux arômes de fruits rouges.',
                    'short_description' => 'Vin rouge 75cl',
                ],
                'en' => [
                    'name' => 'Mercurey 2022 Red - "Les Chavances"',
                    'description' => 'Red wine from Mercurey with red fruit aromas.',
                    'short_description' => 'Red wine 75cl',
                ],
                'pt_BR' => [
                    'name' => 'Mercurey 2022 Tinto - "Les Chavances"',
                    'description' => 'Vinho tinto de Mercurey com aromas de frutas vermelhas.',
                    'short_description' => 'Vinho tinto 75cl',
                ],
                'es' => [
                    'name' => 'Mercurey 2022 Tinto - "Les Chavances"',
                    'description' => 'Vino tinto de Mercurey con aromas de frutos rojos.',
                    'short_description' => 'Vino tinto 75cl',
                ],
                'de' => [
                    'name' => 'Mercurey 2022 Rot - "Les Chavances"',
                    'description' => 'Rotwein aus Mercurey mit Aromen roter Früchte.',
                    'short_description' => 'Rotwein 75cl',
                ],
                'it' => [
                    'name' => 'Mercurey 2022 Rosso - "Les Chavances"',
                    'description' => 'Vino rosso di Mercurey con aromi di frutti rossi.',
                    'short_description' => 'Vino rosso 75cl',
                ],
            ],
        ];

        $successCount = 0;
        $notFoundCount = 0;

        foreach ($translations as $frName => $langs) {
            // Chercher le produit en comparant avec le champ JSON 'fr'
            $product = Product::all()->first(function ($product) use ($frName) {
                return ($product->name['fr'] ?? '') === $frName;
            });
            
            if ($product) {
                foreach ($langs as $locale => $data) {
                    DB::table('product_translations')->updateOrInsert(
                        [
                            'product_id' => $product->id,
                            'locale' => $locale,
                        ],
                        [
                            'name' => $data['name'],
                            'description' => $data['description'],
                            'short_description' => $data['short_description'] ?? null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
                $successCount++;
                $this->command->info("✓ Traductions ajoutées pour: {$frName}");
            } else {
                $notFoundCount++;
                $this->command->warn("⚠ Produit non trouvé: {$frName}");
            }
        }
        
        $this->command->info("=================================");
        $this->command->info("RÉSUMÉ:");
        $this->command->info("✓ {$successCount} produits traduits avec succès");
        if ($notFoundCount > 0) {
            $this->command->warn("⚠ {$notFoundCount} produits non trouvés");
        }
        $this->command->info("=================================");
    }
}