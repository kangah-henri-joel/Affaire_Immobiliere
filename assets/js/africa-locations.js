/**
 * africa-locations.js
 * Base de données complète des 54 pays d'Afrique avec leurs Villes, Communes et Quartiers
 * + Système de cascades dynamiques pour les filtres et formulaires (Home, Liste, Admin, Inscription)
 */

(function () {
    'use strict';

    // ── 1. BASE DE DONNÉES GÉOGRAPHIQUE AFRIQUE ────────────────────────────────
    const AFRICA_LOCATIONS = {
        "Afrique du Sud": {
            "Johannesburg": {
                "Sandton": ["Sandown", "Bryanston", "Morningside", "Rivonia", "Woodmeadow"],
                "Rosebank": ["Melrose Arch", "Parkhurst", "Illovo", "Saxonwold"],
                "Randburg": ["Ferndale", "Bordeaux", "Cresta", "Linden"],
                "Soweto": ["Orlando", "Diepkloof", "Pimville", "Meadowlands"],
                "Midrand": ["Waterfall City", "Halfway House", "Kyalami"]
            },
            "Le Cap (Cape Town)": {
                "City Bowl": ["CBD", "Gardens", "Tamboerskloof", "Bo-Kaap", "Woodstock"],
                "Atlantic Seaboard": ["Camps Bay", "Clifton", "Sea Point", "Green Point", "Bantry Bay"],
                "Southern Suburbs": ["Rondebosch", "Newlands", "Constantia", "Claremont", "Kenilworth"]
            },
            "Pretoria": {
                "Arcadia": ["Union Buildings Area", "Hatfield", "Colbyn"],
                "Centurion": ["Eldoraigne", "Clubview", "Irene", "Rooihuiskraal"],
                "Pretoria East": ["Menlyn", "Faerie Glen", "Lynnwood", "Moreleta Park"]
            },
            "Durban": {
                "Durban North": ["Umhlanga", "La Lucia", "Virginia"],
                "Berea": ["Musgrave", "Morningside", "Glenwood"],
                "Durban South": ["Amanzimtoti", "Bluff", "Chatsworth"]
            }
        },
        "Algérie": {
            "Alger": {
                "Hydra": ["Hydra Centre", "Paradou", "Paradou Supérieur"],
                "Ben Aknoun": ["Cité Malki", "Val d'Hydra", "Djenane El Malik"],
                "El Biar": ["Saint Raphaël", "Tagarins", "Les Crêtes"],
                "Bab El Oued": ["Place des Martyrs", "Triolet", "Triolet Ouest"],
                "Cheraga": ["Bouchaoui", "Dely Ibrahim", "Amara"],
                "Zéralda": ["Cité 1000 Logements", "Sidi Menif", "Sables d'Or"]
            },
            "Oran": {
                "Oran Centre": ["Front de Mer", "Place d'Armes", "Plateau"],
                "Akid Lotfi": ["Cité Akid Lotfi", "Résidence Hasnaoui"],
                "Es Senia": ["Cité Universitaire", "Zone Industrielle"]
            },
            "Constantine": {
                "Centre Ville": ["Cité Filali", "Bellevue", "El Kentara"],
                "Nouvelle Ville Ali Mendjeli": ["Unité d'Habitation 1", "Unité 5", "Unité 14"]
            },
            "Annaba": {
                "Annaba Centre": ["Cours de la Révolution", "Chapuis", "Saint Cloud"]
            }
        },
        "Angola": {
            "Luanda": {
                "Talatona": ["Talatona City", "Miramar", "Condomínio Dolce Vita"],
                "Ingombota": ["Coqueiros", "Mutamba", "Maculusso", "Maianga"],
                "Belas": ["Kilamba", "Benfica", "Camama"],
                "Viana": ["Zango", "Kikuxi", "Viana Sede"]
            },
            "Huambo": {
                "Huambo Centre": ["Bairro Benfica", "Bairro Macolocolo", "Bairro Fatima"]
            },
            "Benguela": {
                "Benguela Centre": ["Bairro Praia Morena", "Bairro 11 de Novembro"]
            },
            "Lobito": {
                "Lobito Centre": ["Restinga", "Bairro Compão", "Bairro Bela Vista"]
            }
        },
        "Bénin": {
            "Cotonou": {
                "Haie Vive": ["Haie Vive Cité", "Cocotiers", "Air France"],
                "Cadjehoun": ["Cadjehoun Kpota", "Cadjehoun Gare", "Voyageurs"],
                "Ganhi": ["Zone Commerciale", "Marina", "Scoa Gbéto"],
                "Akpakpa": ["Dodomey", "Suru Léré", "Avotrou", "Mênontin"],
                "Fidjrossè": ["Fidjrossè Kpota", "Calvaire", "Fiyégnon"],
                "Gbégamey": ["Cité Houeyiho", "Zogbo", "Saint Michel"]
            },
            "Porto-Novo": {
                "Ouando": ["Marché Ouando", "Djassin", "Dowa"],
                "Centre-Ville": ["Avakpa", "Gbecon", "Kandi"]
            },
            "Parakou": {
                "Banikanni": ["Banikanni Centre", "Zongo", "Albarika"],
                "Titirou": ["Titirou 1", "Titirou 2", "Kpébié"]
            },
            "Abomey-Calavi": {
                "Calavi Centre": ["Zogbadjè", "Tankpè", "Togba", "Akassato"]
            }
        },
        "Botswana": {
            "Gaborone": {
                "Gaborone Central": ["Extension 9", "Extension 11", "Main Mall"],
                "Phakalane": ["Phakalane Golf Estate", "Phase 1", "Phase 2"],
                "Gaborone West": ["Phase 1", "Block 6", "Block 8", "Block 10"]
            },
            "Francistown": {
                "Central": ["Molapo Crossing", "Donga", "Ntshe"]
            },
            "Maun": {
                "Matlapaneng": ["Sedia", "Disaneng", "Boseja"]
            }
        },
        "Burkina Faso": {
            "Ouagadougou": {
                "Ouaga 2000": ["Zone Ambassades", "Ouaga 2000 Extension", "Résidence VIP"],
                "Zone du Bois": ["Zone du Bois Centre", "Dassasgho", "Wayalghin"],
                "Patte d'Oie": ["Cissin", "Karpala", "Kalgondin"],
                "Gounghin": ["Gounghin Nord", "Gounghin Sud", "Pissy"],
                "Tampouy": ["Tampouy Nord", "Kilwin", "Larlé"]
            },
            "Bobo-Dioulasso": {
                "Sya": ["Sarfalao", "Accart-Ville", "Koko", "Colma"],
                "Zone Résidentielle": ["Belleville", "Bindougousso", "Bolomakoté"]
            },
            "Koudougou": {
                "Sectoriel": ["Secteur 1", "Secteur 2", "Nyalghin"]
            }
        },
        "Burundi": {
            "Bujumbura": {
                "Mukaza": ["Rohero I", "Rohero II", "Buyenzi", "Bwiza", "Nyakabiga"],
                "Muha": ["Kinindo", "Kanyosha", "Musaga"],
                "Ntahangwa": ["Ngagara", "Cibitoke", "Kamenge", "Kinama", "Gihosha"]
            },
            "Gitega": {
                "Gitega Centre": ["Magarama", "Nyamugari", "Shatanya"]
            }
        },
        "Cameroun": {
            "Douala": {
                "Douala I (Akwa / Bonanjo)": ["Akwa", "Bonanjo", "Deido", "Bali"],
                "Douala II (New Bell)": ["New Bell", "Nkongmondo", "Kassalafam"],
                "Douala III (Bassa / Logbaba)": ["Logbaba", "Ndokoti", "Nyangon", "Nyalla"],
                "Douala IV (Bonabéri)": ["Bonabéri Centre", "Grand Moulin", "Mabanda"],
                "Douala V (Makepe / Bonamoussadi)": ["Bonamoussadi", "Makepe", "Kotto", "Denver", "Ndogbong", "Logpom"]
            },
            "Yaoundé": {
                "Yaoundé I (Bastos)": ["Bastos", "NkolEton", "Tsinga", "Ndrag-Mballa"],
                "Yaoundé II (Tsinga)": ["Tsinga", "Mokolo", "Carrière"],
                "Yaoundé III (Nsam / Efoulan)": ["Nsam", "Efoulan", "Ahala", "Mvan"],
                "Yaoundé IV (Odza / Kondengui)": ["Odza", "Kondengui", "Mimboman", "Ekounou"],
                "Yaoundé V (Essos / Omnisports)": ["Essos", "Omnisports", "Nkolmesseng"],
                "Yaoundé VI (Biyem-Assi)": ["Biyem-Assi", "Mendong", "Simbock"]
            },
            "Bafoussam": {
                "Bafoussam Centre": ["Djeleng", "Kamkop", "Tamdja", "Kouogouo"]
            },
            "Garoua": {
                "Garoua Centre": ["Roumdé Adjia", "Poumpoumré", "Laindé"]
            },
            "Kribi": {
                "Kribi Centre": ["Dombe", "Mboa Manga", "Ngoye", "Tara Plage"]
            }
        },
        "Cap-Vert": {
            "Praia": {
                "Palmarejo": ["Palmarejo Baixo", "Palmarejo Grande"],
                "Achada Santo António": ["Cité Gouvernementale", "Bairro Craveiro"],
                "Prainha": ["Zone Ambassades", "Prainha Plage"],
                "Plateau": ["Centre Historique", "Chã de Areia"]
            },
            "Mindelo": {
                "Centre": ["Avenida Marginal", "Monte Sossego", "Ribeira Bote"]
            }
        },
        "République centrafricaine": {
            "Bangui": {
                "1er Arrondissement": ["Centre-Ville", "Lakouanga", "Siriri"],
                "2ème Arrondissement": ["Sica 1", "Sica 2", "Bégoua"],
                "3ème Arrondissement": ["PK5", "KM5", "Miskine"],
                "4ème Arrondissement": ["Boy-Rabe", "Gobongo", "Fouh"]
            }
        },
        "Comores": {
            "Moroni": {
                "Moroni Centre": ["Badjanani", "Mtsangani", "Coulisses", "Itsandra MDjini"]
            },
            "Mutsamudu": {
                "Mutsamudu Centre": ["Chitrouni", "Pagé", "Mjamaoué"]
            }
        },
        "République du Congo": {
            "Brazzaville": {
                "Poto-Poto": ["Poto-Poto 1", "Poto-Poto 2", "Poto-Poto 3"],
                "Bacongo": ["Bacongo Centre", "Marché Total", "Mpissa"],
                "Makélékélé": ["Matingou", "Château d'Eau", "Kibouende"],
                "Moungali": ["Moungali Centre", "Dix Francs", "Plateau des 15 Ans"],
                "Ouenzé": ["Ouenzé 1", "Ouenzé 2", "Mpila"],
                "Talangaï": ["Talangaï Centre", "Nkombo", "Kintélé"]
            },
            "Pointe-Noire": {
                "Lumumba": ["Centre-Ville", "Côte Sauvage", "Grand Marché"],
                "Tié-Tié": ["Fond Tié-Tié", "Marché Mvou-Mvou"],
                "Mongo-Mpoukou": ["Loandjili", "Vindoulou"]
            }
        },
        "République démocratique du Congo": {
            "Kinshasa": {
                "Gombe": ["Gombe Business", "Golf", "Résidence Fleuve Congo", "Cité du Fleuve"],
                "Ngaliema": ["Ma Campagne", "Binza Macampagne", "Binza Pigeon", "Binza IPN", "UPN"],
                "Kintambo": ["Kintambo Magasin", "Jamaique"],
                "Bandalungwa": ["Bandal Makelele", "Bandal Tshibangu", "Bandal Synkin"],
                "Lingwala": ["Palais du Peuple", "Triomphal"],
                "Lemba": ["Lemba Super", "Lemba Foire", "Righini", "Université de Kinshasa"],
                "Ndjili": ["Quartier 1", "Quartier 4", "Quartier 7"],
                "Masina": ["Sans Fil", "Siforco", "Petro Congo"]
            },
            "Lubumbashi": {
                "Lubumbashi Centre": ["Golf", "Golf Meteo", "Golf Faustin", "Carrefour"],
                "Kampemba": ["Bel-Air", "Kafubu"],
                "Annexe": ["Kasapa", "Kabuya"]
            },
            "Goma": {
                "Goma Centre": ["Himbi", "Karisimbi", "Mikeno", "Katindo", "Les Volcans"]
            },
            "Kisangani": {
                "Makiso": ["Centre Ville", "Plateau Medical"]
            }
        },
        "Côte d'Ivoire": {
            "Abidjan": {
                "Cocody": ["Angré", "Riviera 1", "Riviera 2", "Riviera 3", "Riviera 4", "Riviera 5", "Riviera 6", "2 Plateaux", "Palmeraie", "Bonoumin", "Danga", "Ambassades", "Vallon", "M'Pouto", "M'Badon", "Saint Jean", "Attoban", "Génie 2000", "Djibi"],
                "Yopougon": ["Niangon", "Maroc", "Bel Air", "Kouté", "Toits Rouges", "Selmer", "Académie", "Wassakara", "Sideci", "Nouveau Quartier", "Andokoi", "Millionnaire", "Port-Bouët 2", "Santai"],
                "Marcory": ["Zone 4", "Biétry", "Anoumabo", "Résidentiel", "Champroux", "Remblais", "Hibiscus"],
                "Abobo": ["Sagbé", "PK18", "N'Dotré", "Avocatier", "Anador", "Houantoué", "Depot", "Belle Ville", "Akeikoi"],
                "Port-Bouët": ["Gonzaqueville", "Vridi", "Derrière L'Appartement", "Phare", "Centre", "Abjou-Cité"],
                "Plateau": ["Centre des Affaires", "Cité Administrative", "Banques", "Pygmalion"],
                "Adjamé": ["Château", "Mirador", "220 Logements", "Siporex", "Renault"],
                "Treichville": ["Arras", "Avenue 16", "Zone Industrielle", "Belleville"],
                "Koumassi": ["Remblais", "Camp Commando", "Grand Campement", "Zattry"],
                "Attécoubé": ["Locodjro", "Abobo-Doumé", "Santé"],
                "Bingerville": ["Cité FEU", "Akandjé", "Savane", "Blokhauss", "ECA"]
            },
            "Yamoussoukro": {
                "Centre-Ville": ["Morofé", "Habitat", "220 Logements", "Fondation", "N'Gokro", "Koko", "Zaher", "Residentiel"]
            },
            "Bouaké": {
                "Centre-Ville": ["Air France 1", "Air France 2", "Koko", "Nimbo", "Belleville", "Dar-es-Salam", "Kennedy", "Broukro", "Ahougnanssou"]
            },
            "San-Pédro": {
                "San-Pédro Centre": ["Cité", "Bardot", "Balmer", "Seweke", "Zone Industrielle"]
            },
            "Korhogo": {
                "Korhogo Centre": ["Koko", "Soba", "Haoussabougou", "Prefecture", "Sinistré"]
            },
            "Daloa": {
                "Daloa Centre": ["Tazibouo", "Lobia", "Gbeuliville", "Kennedy"]
            },
            "Man": {
                "Man Centre": ["Lycée", "Grand Gbapleu", "Doyagouiné", "Koko"]
            },
            "Gagnoa": {
                "Gagnoa Centre": ["Babré", "Garahio", "Zapredine", "Dioulabougou"]
            },
            "Abengourou": {
                "Abengourou Centre": ["Agni-Mansou", "Relais", "Plateau", "Indénié"]
            },
            "Grand-Bassam": {
                "Grand-Bassam Centre": ["Quartier France", "Moossou", "Imperial", "Phare"]
            }
        },
        "Djibouti": {
            "Djibouti": {
                "Djibouti Ville": ["Héron", "Marabout", "Haramous", "Boulaos", "Gabode", "Balbala"]
            }
        },
        "Égypte": {
            "Le Caire": {
                "Zamalek": ["Gezira", "Zamalek North", "Zamalek South"],
                "Maadi": ["Degla Maadi", "Old Maadi", "Zahraa Maadi", "Corniche Maadi"],
                "New Cairo": ["Fifth Settlement", "First Settlement", "Katameya Heights", "Rehab City", "Madinaty"],
                "Heliopolis": ["Korba", "Heliopolis West", "Almaza"],
                "Nasr City": ["Zone 6", "Zone 7", "Zone 8", "Zone 9", "Zone 10"],
                "6th of October": ["Sheikh Zayed City", "October Gardens", "West Somid"]
            },
            "Alexandrie": {
                "Montaza": ["Maamoura", "Mandara", "Asafra", "Miamis"],
                "East Alexandria": ["Roushdy", "Kafr El Abdo", "Stanley", "Glim"]
            }
        },
        "Érythrée": {
            "Asmara": {
                "Asmara Centre": ["Tiravolo", "Mai Temenai", "Gejeret", "Campo Polo", "Edaga Hamus"]
            }
        },
        "Eswatini": {
            "Mbabane": {
                "Mbabane Centre": ["Eveni", "Highland View", "Themba", "Waterford"]
            },
            "Manzini": {
                "Manzini Centre": ["Fairviews", "Matsapha", "Ngwane Park"]
            }
        },
        "Éthiopie": {
            "Addis-Abeba": {
                "Bole": ["Bole Medhanealem", "Bole Atlas", "Bole Rwanda", "Bole Japan"],
                "Kirkos": ["Kazanchis", "Meskel Square", "Gotera"],
                "Arada": ["Piassa", "4 Kilo", "6 Kilo"],
                "Nifas Silk-Lafto": ["Old Airport", "Lebu", "Lafto"]
            },
            "Dire Dawa": {
                "Kebele": ["Kebele 01", "Kebele 02", "Kazira"]
            }
        },
        "Gabon": {
            "Libreville": {
                "Akanda": ["Sablière", "Angondjé", "Cap Estérias", "Sherko"],
                "1er Arrondissement": ["Batterie IV", "Haut de Gué-Gué", "Bas de Gué-Gué", "Louis", "Charbonnages"],
                "2ème Arrondissement": ["Nombakélé", "Camp de Police", "Atsoungou"],
                "3ème Arrondissement": ["Mont-Bouët", "Plaine Orety", "Kinguélé"],
                "4ème Arrondissement": ["Glass", "Baraka", "Toulon"],
                "5ème Arrondissement": ["Glass", "Mindoubé", "Lalala"],
                "6ème Arrondissement": ["Nzeng-Ayong", "PK 8", "PK 12"],
                "Owendo": ["Cité Octra", "Viré", "Port Owendo"]
            },
            "Port-Gentil": {
                "Port-Gentil Centre": ["Grand Village", "Château", "Balise", "Zone Industrielle"]
            },
            "Franceville": {
                "Franceville Centre": ["Potos", "Moungali", "Poussières"]
            }
        },
        "Gambie": {
            "Banjul": {
                "Banjul Centre": ["Half Die", "Soldier Town"]
            },
            "Serekunda": {
                "Bakau / Fajara": ["Fajara Plage", "Bakau Cape Point", "Kotu"],
                "Kololi / Bijilo": ["Senegambia Strip", "Kololi Plage", "Bijilo Beach"],
                "Kanifing": ["Kanifing Estate", "Pipelines", "Latrikunda"]
            }
        },
        "Ghana": {
            "Accra": {
                "East Legon": ["East Legon Hills", "Shiashie", "American House", "Anaji"],
                "Cantonments": ["Cantonments VIP", "Labone", "Rangoon"],
                "Airport Residential": ["Airport City", "Roman Ridge", "Dzorwulu"],
                "Osu": ["Osu Oxford Street", "RE", "Kuku Hill"],
                "Spintex": ["Spintex Road", "Baatsona", "Sakumono"]
            },
            "Kumasi": {
                "Kumasi Central": ["Ahodwo", "Asokwa", "Nhyiaeso", "Ridge"]
            },
            "Takoradi": {
                "Takoradi Central": ["Anaji", "Beach Road", "Airport Ridge"]
            }
        },
        "Guinée": {
            "Conakry": {
                "Kaloum": ["Centre-Ville", "Almamya", "Boulbinet", "Kouléwondy"],
                "Dixinn": ["Landréah", "Camayenne", "Dixinn Port", "Minière"],
                "Ratoma": ["Kipé", "Lambanyi", "Nongo", "Taouyah", "Cosa", "Hamdallaye", "Kaporo"],
                "Matam": ["Madina", "Bonfi", "Coleah", "Matam Centre"],
                "Matoto": ["Sonfonia", "Entag", "Yimbaya", "Tombolia", "Cité de l'Air"]
            },
            "Nzérékoré": {
                "Nzérékoré Centre": ["Dorota", "Commercial", "Bowie"]
            },
            "Kankan": {
                "Kankan Centre": ["Kabada", "Timbo", "Mbalia"]
            }
        },
        "Guinée-Bissau": {
            "Bissau": {
                "Bissau Centre": ["Bairro de Penha", "Bandim", "Missira", "Santa Luzia", "Praça dos Heróis"]
            }
        },
        "Guinée équatoriale": {
            "Malabo": {
                "Malabo Centre": ["Ela Nguema", "Sampaka", "Caracolas", "Buena Esperanza", "Paraíso"]
            },
            "Bata": {
                "Bata Centre": ["Nkuantoma", "Bomudi", "Biyitem"]
            }
        },
        "Kenya": {
            "Nairobi": {
                "Westlands": ["Parklands", "Spring Valley", "Muthangari", "Kitisuru"],
                "Kilimani": ["Kilimani Centre", "Hurlingham", "Yaya"],
                "Lavington": ["Lavington Green", "Bernhard Estate", "Kileleshwa"],
                "Karen": ["Karen Triangle", "Langata", "Hardy"],
                "Runda": ["Runda Mimosa", "Runda Evergreen", "Gigiri"],
                "Upper Hill": ["Community", "Hospital Hill"]
            },
            "Mombasa": {
                "Nyali": ["Nyali Beach", "Cinemax", "Old Nyali"],
                "Mombasa Island": ["Ganjoni", "Tudor", "Kizingo"]
            }
        },
        "Lesotho": {
            "Maseru": {
                "Maseru Centre": ["Maseru West", "Hillsview", "Florida", "Stadium Area", "Thetsane"]
            }
        },
        "Libéria": {
            "Monrovia": {
                "Monrovia Centre": ["Mamba Point", "Sinkor 1-20", "Congotown", "Paynesville", "Bushrod Island"]
            }
        },
        "Libye": {
            "Tripoli": {
                "Tripoli Centre": ["Hay al-Andalus", "Ben Ashour", "Dimashq", "Gurgi", "Sidi El Masri"]
            },
            "Benghazi": {
                "Benghazi Centre": ["Al-Fuwayhat", "Al-Sabri", "Tabruk"]
            }
        },
        "Madagascar": {
            "Antananarivo": {
                "1er Arrondissement": ["Isoraka", "Antaninarenina", "Analakely", "Tsaralalana"],
                "2ème Arrondissement": ["Ambanidia", "Ambohipo", "Andohalo"],
                "3ème Arrondissement": ["Antanimena", "Ankorondrano", "Behoririka"],
                "4ème Arrondissement": ["Mahamasina", "Ankadimbahoaka", "Isotry"],
                "5ème Arrondissement": ["Ivandry", "Alarobia", "Ambatobe", "Nanisana"],
                "6ème Arrondissement": ["Ambohibao", "Ivato", "Talatamaty"]
            },
            "Toamasina": {
                "Toamasina Centre": ["Bazar Be", "Bazar Kely", "Tanambao"]
            },
            "Nosy Be": {
                "Hell-Ville": ["Ambatoloaka", "Madirokely", "Andilana"]
            }
        },
        "Malawi": {
            "Lilongwe": {
                "Lilongwe City": ["Area 10", "Area 11", "Area 12", "Area 43", "City Centre"]
            },
            "Blantyre": {
                "Blantyre City": ["Namiwawa", "Nyambadwe", "Sunnyside", "Mandala"]
            }
        },
        "Mali": {
            "Bamako": {
                "Commune I": ["Korofina", "Banconi", "Djelibougou", "Sotuba"],
                "Commune II": ["Hippodrome", "Quinzambougou", "Bagadadji", "Bozola"],
                "Commune III": ["Bamako Coura", "Darsalam", "N'Tomikorobougou", "Point G"],
                "Commune IV": ["Hamdallaye", "ACI 2000", "Lassa", "Sébénikoro"],
                "Commune V": ["Badalabougou", "Baco-Djicoroni", "Torokorobougou", "Quartier Mali", "Daoudabougou"],
                "Commune VI": ["Faladié", "Niamakoro", "Sogoniko", "Magnambougou", "Yirimadio"],
                "Kati / Kalaban-Coro": ["Kalaban-Coro", "Kabala", "Titibougou"]
            },
            "Sikasso": {
                "Sikasso Centre": ["Wayerma", "Mamelon", "Bougoula"]
            },
            "Ségou": {
                "Ségou Centre": ["Mission", "Somono", "Ségou-Koro"]
            },
            "Mopti": {
                "Mopti Centre": ["Komoguel", "Gangarna", "Bambara"]
            }
        },
        "Maroc": {
            "Casablanca": {
                "Anfa / Gauthier": ["Gauthier", "Racine", "Triangle d'Or", "Bourgogne"],
                "Maârif": ["Maârif Extension", "Les Princesses", "Val Fleuri"],
                "Hay Hassani": ["Oasis", "California", "CIL", "Oulfa"],
                "Ain Diab": ["Corniche", "Sindibad", "Anfa Supérieur"],
                "Sidi Belyout": ["Centre-Ville", "Port", "Foncière"],
                "Mohammedia": ["La Siesta", "Monica", "Mansouria"]
            },
            "Rabat": {
                "Agdal": ["Agdal Haut", "Agdal Bas"],
                "Souissi": ["Souissi Ambassades", "Bir Kacem"],
                "Hassan": ["Centre-Ville", "Oudayas"],
                "Hay Riad": ["Prestigia", "Secteur 10-20"]
            },
            "Marrakech": {
                "Hivernage": ["Avenue Mohammed VI", "Hivernage Plage"],
                "Gueliz": ["Plaza", "Carré Eden"],
                "Palmeraie": ["Circuit de la Palmeraie", "Bab Atlas"],
                "Médina": ["Riad Laarouss", "Kasbah", "Mellah"]
            },
            "Tanger": {
                "Malabata": ["Malabata Hills", "Baie de Tanger"],
                "Centre": ["Boulevard", "Iberia", "Marshanc"]
            }
        },
        "Maurice": {
            "Port-Louis": {
                "Port-Louis Centre": ["Le Caudan Waterfront", "Champ de Mars", "Tranquebar"]
            },
            "Quatre Bornes": {
                "Quatre Bornes Centre": ["Sollferino", "Belle Rose", "Vieux Quatre Bornes"]
            },
            "Grand Baie": {
                "Grand Baie Centre": ["Péreybère", "Pointe aux Canonniers", "Trou aux Biches"]
            },
            "Flic en Flac": {
                "Flic en Flac Plage": ["Wolmar", "Morcellement Safeland"]
            }
        },
        "Mauritanie": {
            "Nouakchott": {
                "Tevragh-Zeina": ["Tevragh-Zeina Centre", "Ilot K", "Las Palmas", "Cité Plage", "Cité SOCOGIM"],
                "Ksar": ["Ksar Nord", "Ksar Sud", "Aéroport"],
                "Sebkha": ["Sebkha Centre", "Cinéma Saada"],
                "Arafat": ["Poteau 6", "Carrefour Madrid"],
                "Dar Naim": ["Dar Naim Centre", "Zone Verte"]
            },
            "Nouadhibou": {
                "Nouadhibou Centre": ["Cansado", "Numerowat", "Dubai"]
            }
        },
        "Mozambique": {
            "Maputo": {
                "Polana Canhiço": ["Polana Cimento A", "Polana Cimento B", "Sommerchield I", "Sommerchield II"],
                "Triunfo": ["Triunfo 1", "Triunfo 2", "Costa do Sol"],
                "Central": ["Alto Maé", "Baixa", "Coop"]
            },
            "Matola": {
                "Matola Centre": ["Matola Gare", "Fomento", "Machava"]
            }
        },
        "Namibie": {
            "Windhoek": {
                "Windhoek East": ["Ludwigsdorf", "Klein Windhoek", "Eros", "Auasblick", "Olympia"],
                "Windhoek Central": ["CBD", "Khomasdal", "Katutura"]
            },
            "Swakopmund": {
                "Swakopmund Centre": ["Vogelstrand", "Ocean View", "Mile 4"]
            }
        },
        "Niger": {
            "Niamey": {
                "Niamey I": ["Plateau", "Yantala Haut", "Yantala Bas"],
                "Niamey II": ["Koira Kano", "Koira Tegui", "Sonuci", "Cité Députés"],
                "Niamey III": ["Dar Es Salam", "Lazaret", "Katako"],
                "Niamey IV": ["Talladjé", "Aéroport", "Saga"],
                "Niamey V": ["Harobanda", "Kirkissoye"]
            },
            "Zinder": {
                "Zinder Centre": ["Birni", "Sabon Gari", "Zango"]
            },
            "Maradi": {
                "Maradi Centre": ["Zaria", "Zongo", "Dan Zaria"]
            }
        },
        "Nigeria": {
            "Lagos": {
                "Victoria Island / Ikoyi": ["Banana Island", "Ikoyi GRA", "Parkview Estate", "Victoria Island Annex"],
                "Lekki": ["Lekki Phase 1", "Chevron", "Ikate", "Agungi", "Ajah", "VGC"],
                "Ikeja": ["Ikeja GRA", "Allen Avenue", "Opebi", "Oregun", "Computer Village"],
                "Surulere": ["Adeniran Ogunsanya", "Bodija", "Bode Thomas"],
                "Yaba": ["Akoka", "Sabo", "Tejuosho"]
            },
            "Abuja": {
                "Maitama": ["Maitama Main", "Diplomatic Zone"],
                "Asokoro": ["Asokoro Extension", "Guzape"],
                "Wuse": ["Wuse 2", "Wuse Zone 1-7"],
                "Gwarinpa": ["Gwarinpa Estate", "Life Camp"],
                "Jabi": ["Jabi Lake", "Utako"]
            },
            "Ibadan": {
                "Ibadan Central": ["Bodija", "Iyaganku GRA", "Jericho", "Ring Road"]
            },
            "Port Harcourt": {
                "Port Harcourt Central": ["GRA Phase 1-3", "Old GRA", "Trans Amadi"]
            }
        },
        "Ouganda": {
            "Kampala": {
                "Central Division": ["Nakasero", "Kololo", "Old Kampala", "Kivulu"],
                "Nakawa Division": ["Bugolobi", "Naguru", "Ntinda", "Mbuya", "Kyambogo"],
                "Makindye Division": ["Muyenga", "Buziga", "Ggaba", "Kansanga"],
                "Rubaga Division": ["Lungujja", "Mengos", "Namirembe"]
            },
            "Entebbe": {
                "Entebbe Town": ["Kitoro", "Bugonga", "Lake Victoria View"]
            }
        },
        "Rwanda": {
            "Kigali": {
                "Gasabo": ["Nyarutarama", "Gacuriro", "Kagugu", "Kimironko", "Remera", "Kibagabaga"],
                "Nyarugenge": ["Kiyovu", "Nyarugenge CBD", "Nyamirambo", "Gitega"],
                "Kicukiro": ["Kanombe", "Kicukiro Centre", "Gahanga", "Niboye", "Kicukiro Kagarama"]
            },
            "Gisenyi (Rubavu)": {
                "Gisenyi Centre": ["Rubavu Beach", "Grande Barrière"]
            }
        },
        "Sao Tomé-et-Principe": {
            "São Tomé": {
                "São Tomé Centre": ["Quinta Santo António", "Riboque", "Fraternidade", "Pantufo"]
            }
        },
        "Sénégal": {
            "Dakar": {
                "Almadies / Ngor / Yoff": ["Almadies VIP", "Ngor Virage", "Yoff Aredor", "Nord Foire", "Oest Foire", "Virage Beach"],
                "Dakar Plateau": ["Centre des Affaires", "Corniche Ouest", "Corniche Est", "Cap Manuel"],
                "Fann / Point E / Mermoz": ["Fann Résidence", "Point E", "Mermoz", "Sacré-Cœur 1-3"],
                "Médina / Fass / Colobane": ["Médina", "Fass", "Gueule Tapée", "Colobane"],
                "Grand Yoff / Parcelles": ["Parcelles Assainies U1-26", "Grand Yoff", "Liberté 1-6", "Dieuppeul"],
                "Pikine / Guédiawaye": ["Pikine Est", "Guédiawaye Centre", "Keur Massar"],
                "Rufisque": ["Rufisque Centre", "Bargny", "Diamniadio"]
            },
            "Thiès": {
                "Thiès Centre": ["Dixième", "Mbour 1-3", "Randoulène", "Champ de Courses"]
            },
            "Saly / Mbour": {
                "Saly Portudal": ["Saly Niakh Niakhal", "Saly Station", "Ngaparou", "Somone"]
            },
            "Saint-Louis": {
                "Saint-Louis Centre": ["Ile de Saint-Louis", "Ndar Tout", "Sor", "Goxu Mbathie"]
            },
            "Ziguinchor": {
                "Ziguinchor Centre": ["Escale", "Boucotte", "Néma", "Kenfenda"]
            }
        },
        "Seychelles": {
            "Victoria": {
                "Victoria Centre": ["Bel Air", "Saint Louis", "Mont Fleuri", "Beau Vallon", "Eden Island"]
            }
        },
        "Sierra Leone": {
            "Freetown": {
                "West End": ["Hill Station", "Aberdeen", "Lumley Beach", "Juba", "Spur Loop"],
                "Central Freetown": ["Tower Hill", "Wilberforce", "Congo Town"]
            }
        },
        "Somalie": {
            "Mogadiscio": {
                "Mogadiscio Centre": ["Hodan", "Waberi", "Hamar Weyne", "Abdulaziz", "Karan"]
            },
            "Hargeisa": {
                "Hargeisa Centre": ["26 June", "Ga'an Libah", "Ibrahim Koodbuur"]
            }
        },
        "Soudan": {
            "Khartoum": {
                "Khartoum Central": ["Riyadh", "Garden City", "Amarat", "Khartoum 2", "Al-Manshiya"],
                "Omdourman": ["Al-Thawra", "Al-Oula", "Al-Mulazimin"]
            }
        },
        "Soudan du Sud": {
            "Djouba (Juba)": {
                "Juba Central": ["Tongping", "Juba Na Bari", "Hai Commercial", "Munuki", "Gudele"]
            }
        },
        "Tanzanie": {
            "Dar es Salaam": {
                "Kinondoni": ["Masaki", "Oysterbay", "Msasani", "Mikocheni", "Mbezi Beach", "Kawe"],
                "Ilala": ["Upanga East", "Upanga West", "Kariakoo", "City Centre"],
                "Temeke": ["Kigamboni", "Mbagala", "Chang'ombe"]
            },
            "Arusha": {
                "Arusha Centre": ["Njiro", "Sakina", "Sekei", "Sanawari"]
            },
            "Zanzibar": {
                "Stone Town": ["Shangani", "Malindi", "Kiponda", "Nungwi Beach", "Kendwa"]
            }
        },
        "Tchad": {
            "N'Djamena": {
                "1er Arrondissement": ["Farcha", "Milezi"],
                "2ème Arrondissement": ["Moursal", "Paris Congo"],
                "3ème Arrondissement": ["Sabangali", "Kabalaye"],
                "4ème Arrondissement": ["Blabline", "Chagoua"],
                "7ème Arrondissement": ["Atrone", "Dembé", "Gassi"]
            },
            "Moundou": {
                "Moundou Centre": ["Dombao", "Djarabé", "Baguirmi"]
            }
        },
        "Togo": {
            "Lomé": {
                "Golfe 1 (Bè / Baguida)": ["Bè", "Baguida", "Kagomé", "Zone Portuaire"],
                "Golfe 2 (Hédzranawoé)": ["Hédzranawoé", "Tokoin", "N'kafou", "Kégué"],
                "Golfe 3 (Tokoin)": ["Tokoin Doumasséssé", "Cacaveli", "Ramco"],
                "Golfe 4 (Nyékonakpoè)": ["Nyékonakpoè", "Kodjoviakopé", "Amoutivé", "Hanoukopé"],
                "Golfe 5 (Aflao Gakli)": ["Adidogomé", "Djidjolé", "Totsi", "Aflao"],
                "Golfe 6 (Agoè Nyivé)": ["Agoè Centre", "Agoè Assiyéyé", "Vakpossito", "Fidokpui"]
            },
            "Kara": {
                "Kara Centre": ["Lama", "Château", "Lassa", "Ketao"]
            },
            "Sokodé": {
                "Sokodé Centre": ["Komah", "Tchaoudjo", "Kouma"]
            },
            "Kpalimé": {
                "Kpalimé Centre": ["Zomayi", "Kpodzi", "Kuma Konda"]
            }
        },
        "Tunisie": {
            "Tunis": {
                "Les Berges du Lac": ["Lac 1", "Lac 2", "Les Jardins du Lac"],
                "La Marsa": ["La Marsa Ville", "Marsa Plage", "Marsa Cube", "Gammarth"],
                "Carthage / Sidi Bou Saïd": ["Carthage Presidence", "Carthage Dermech", "Sidi Bou Saïd"],
                "El Menzah / Ennasr": ["El Menzah 1-9", "Ennasr 1", "Ennasr 2", "Mutuelleville"],
                "Tunis Centre": ["Avenue Habib Bourguiba", "Lafayette", "Belvédère"]
            },
            "Sousse": {
                "Sousse Centre": ["Kantaoui", "Khzama", "Sahloul", "Bouhsina"]
            },
            "Sfax": {
                "Sfax Centre": ["Route de Téniour", "Route de La Soukra", "El Jardin"]
            }
        },
        "Zambie": {
            "Lusaka": {
                "Lusaka East": ["Kabulonga", "Roma", "Sunningdale", "Ibex Hill", "Woodlands"],
                "Lusaka Central": ["Rhodes Park", "Mass Media", "Longacres", "Kalundu"]
            },
            "Livingstone": {
                "Livingstone Town": ["Victoria Falls Area", "Maramba", "Highlands"]
            }
        },
        "Zimbabwe": {
            "Harare": {
                "Northern Suburbs": ["Borrowdale", "Borrowdale Brooke", "Avondale", "Mount Pleasant", "Highlands", "Gunhill"],
                "Central": ["CBD", "Milton Park", "Belvedere"]
            },
            "Bulawayo": {
                "Bulawayo Central": ["Suburbs", "Kumalo", "Hillside", "Burnside"]
            },
            "Victoria Falls": {
                "Township": ["Chinotimba", "Woodland", "Elephant Hills"]
            }
        }
    };

    // ── 2. FONCTIONS DE RECHERCHE DE DONNÉES ───────────────────────────────────

    function getCountries() {
        return Object.keys(AFRICA_LOCATIONS).sort((a, b) => a.localeCompare(b, 'fr'));
    }

    function getCities(country) {
        if (!country || !AFRICA_LOCATIONS[country]) {
            // Retourner toutes les principales villes si pas de pays choisi
            let allCities = [];
            Object.keys(AFRICA_LOCATIONS).forEach(c => {
                allCities = allCities.concat(Object.keys(AFRICA_LOCATIONS[c]));
            });
            return Array.from(new Set(allCities)).sort((a, b) => a.localeCompare(b, 'fr'));
        }
        return Object.keys(AFRICA_LOCATIONS[country]).sort((a, b) => a.localeCompare(b, 'fr'));
    }

    function getCommunes(country, city) {
        if (country && AFRICA_LOCATIONS[country]) {
            if (city && AFRICA_LOCATIONS[country][city]) {
                return Object.keys(AFRICA_LOCATIONS[country][city]).sort((a, b) => a.localeCompare(b, 'fr'));
            }
            // Si pas de ville spécifiée dans ce pays, agréger les communes
            let cityCommunes = [];
            Object.keys(AFRICA_LOCATIONS[country]).forEach(v => {
                cityCommunes = cityCommunes.concat(Object.keys(AFRICA_LOCATIONS[country][v]));
            });
            return Array.from(new Set(cityCommunes)).sort((a, b) => a.localeCompare(b, 'fr'));
        }
        // Fallback global
        if (city) {
            let foundCommunes = [];
            Object.keys(AFRICA_LOCATIONS).forEach(c => {
                if (AFRICA_LOCATIONS[c][city]) {
                    foundCommunes = foundCommunes.concat(Object.keys(AFRICA_LOCATIONS[c][city]));
                }
            });
            if (foundCommunes.length > 0) {
                return Array.from(new Set(foundCommunes)).sort((a, b) => a.localeCompare(b, 'fr'));
            }
        }
        return [];
    }

    function getQuartiers(country, city, commune) {
        let quartiers = [];
        // Cas 1 : Pays, Ville, Commune définis
        if (country && city && commune && AFRICA_LOCATIONS[country]?.[city]?.[commune]) {
            return AFRICA_LOCATIONS[country][city][commune];
        }
        // Cas 2 : Recherche par Commune seule ou Ville+Commune
        Object.keys(AFRICA_LOCATIONS).forEach(c => {
            if (country && c !== country) return;
            Object.keys(AFRICA_LOCATIONS[c]).forEach(v => {
                if (city && v !== city) return;
                Object.keys(AFRICA_LOCATIONS[c][v]).forEach(com => {
                    if (!commune || com.toLowerCase().includes(commune.toLowerCase())) {
                        quartiers = quartiers.concat(AFRICA_LOCATIONS[c][v][com]);
                    }
                });
            });
        });
        return Array.from(new Set(quartiers)).sort((a, b) => a.localeCompare(b, 'fr'));
    }

    // ── 3. MISE À JOUR POPULATION DES DATALISTS ────────────────────────────────

    function updateDatalist(datalistId, items) {
        const datalist = document.getElementById(datalistId);
        if (!datalist) return;
        datalist.innerHTML = '';
        const fragment = document.createDocumentFragment();
        items.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item;
            fragment.appendChild(opt);
        });
        datalist.appendChild(fragment);
    }

    // ── 4. ATTACHEMENT DU CASCADING SUR UN GROUPE DE CHAMPS ────────────────────

    function bindCascadingGroup(config) {
        const inputPays     = document.querySelector(config.paysSelector);
        const datalistPays  = config.paysDatalistId;
        const inputVille    = document.querySelector(config.villeSelector);
        const datalistVille = config.villeDatalistId;
        const inputCommune  = document.querySelector(config.communeSelector);
        const datalistCommune = config.communeDatalistId;
        const inputQuartier = document.querySelector(config.quartierSelector);
        const datalistQuartier = config.quartierDatalistId;

        // Populate initiales
        updateDatalist(datalistPays, getCountries());

        function refreshAll() {
            const currentPays = inputPays ? inputPays.value.trim() : '';
            const currentVille = inputVille ? inputVille.value.trim() : '';
            const currentCommune = inputCommune ? inputCommune.value.trim() : '';

            updateDatalist(datalistVille, getCities(currentPays));
            updateDatalist(datalistCommune, getCommunes(currentPays, currentVille));
            updateDatalist(datalistQuartier, getQuartiers(currentPays, currentVille, currentCommune));
        }

        refreshAll();

        if (inputPays) {
            inputPays.addEventListener('input', () => {
                refreshAll();
            });
            inputPays.addEventListener('change', refreshAll);
        }

        if (inputVille) {
            inputVille.addEventListener('input', () => {
                const currentPays = inputPays ? inputPays.value.trim() : '';
                const currentVille = inputVille.value.trim();
                updateDatalist(datalistCommune, getCommunes(currentPays, currentVille));
                updateDatalist(datalistQuartier, getQuartiers(currentPays, currentVille, inputCommune ? inputCommune.value.trim() : ''));
            });
            inputVille.addEventListener('change', refreshAll);
        }

        if (inputCommune) {
            inputCommune.addEventListener('input', () => {
                const currentPays = inputPays ? inputPays.value.trim() : '';
                const currentVille = inputVille ? inputVille.value.trim() : '';
                const currentCommune = inputCommune.value.trim();
                updateDatalist(datalistQuartier, getQuartiers(currentPays, currentVille, currentCommune));
            });
        }
    }

    // ── 5. INITIALISATION AUTOMATIQUE SUR LE DOM ───────────────────────────────

    document.addEventListener('DOMContentLoaded', () => {
        // A. Filtres de la page Liste d'Annonces (/annonces)
        bindCascadingGroup({
            paysSelector: '#filterPays, input[name="pays"]',
            paysDatalistId: 'list-pays',
            villeSelector: '#filterVille, input[name="ville"]',
            villeDatalistId: 'list-villes',
            communeSelector: '#filterCommune, input[name="commune"]',
            communeDatalistId: 'list-communes',
            quartierSelector: '#filterQuartier, input[name="quartier"]',
            quartierDatalistId: 'list-quartiers'
        });

        // B. Formulaire d'administration (/admin/annonces)
        bindCascadingGroup({
            paysSelector: '#addAnnonceModal input[name="pays"]',
            paysDatalistId: 'admin-list-pays',
            villeSelector: '#addAnnonceModal input[name="ville"]',
            villeDatalistId: 'admin-list-villes',
            communeSelector: '#addAnnonceModal input[name="commune"]',
            communeDatalistId: 'admin-list-communes',
            quartierSelector: '#addAnnonceModal input[name="quartier"]',
            quartierDatalistId: 'admin-list-quartiers'
        });

        // C. Formulaire Hero de la page d'accueil (Home)
        bindCascadingGroup({
            paysSelector: '#heroPays, .search-form-pro input[name="pays"]',
            paysDatalistId: 'hero-pays',
            villeSelector: '#heroVille, .search-form-pro input[name="ville"]',
            villeDatalistId: 'hero-villes',
            communeSelector: '#heroCommune, .search-form-pro input[name="commune"]',
            communeDatalistId: 'hero-communes',
            quartierSelector: '#heroQuartier, .search-form-pro input[name="quartier"], .search-form-pro input[name="query"]',
            quartierDatalistId: 'hero-quartiers'
        });

        // D. Formulaire d'inscription (Login/Register)
        const registerCountry = document.querySelector('#tab-register input[name="country"]');
        if (registerCountry) {
            registerCountry.setAttribute('list', 'list-pays');
        }
    });

    // Exposer l'objet globalement pour utilisation personnalisée si besoin
    window.AFRICA_LOCATIONS = AFRICA_LOCATIONS;
})();
