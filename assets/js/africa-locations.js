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
                "Cocody": ["Angré", "Angré 7ème Tranche", "Angré 8ème Tranche", "Angré 9ème Tranche", "Angré Château", "Angré Djibi", "Riviera 1", "Riviera 2", "Riviera 3", "Riviera 4", "Riviera 5", "Riviera Golf", "Riviera Bonoumin", "Riviera Palmeraie", "Riviera Faya", "Deux Plateaux", "Deux Plateaux Vallon", "Deux Plateaux Aghien", "Deux Plateaux 7ème Tranche", "Danga", "Ambassades", "M'Pouto", "M'Badon", "Anono", "Saint Jean", "Attoban", "Génie 2000", "Abatta"],
                "Bingerville": ["Feh Kessé", "Akandjé", "Cité FEU", "Savane", "Blokhauss", "ECA", "Marché", "Adjamé-Bingerville", "Sebroko", "Gbagba"],
                "Marcory": ["Zone 4", "Biétry", "Anoumabo", "Marcory Résidentiel", "Champroux", "Remblais", "Hibiscus", "Aliodan", "INJS", "Konankro"],
                "Yopougon": ["Niangon Nord", "Niangon Sud", "Maroc", "Bel Air", "Kouté", "Toits Rouges", "Selmer", "Académie", "Wassakara", "Sideci", "Nouveau Quartier", "Andokoi", "Millionnaire", "Port-Bouët 2", "Santai", "Gesco", "Siporex", "Zone Industrielle", "Camp Militaire", "Lièvre Rouge"],
                "Plateau": ["Centre des Affaires", "Cité Administrative", "Boulevard Lagunaire", "Banques", "Pygmalion", "Sodeci"],
                "Port-Bouët": ["Gonzaqueville", "Vridi", "Vridi Canal", "Derrière Wharf", "Phare", "Centre", "Abjou-Cité", "Adjouffou", "Anani", "Jean Folly", "Aéroport"],
                "Koumassi": ["Remblais", "Camp Commando", "Grand Campement", "Zattry", "Sopim", "Divo", "Acaly", "05"],
                "Treichville": ["Arras", "Avenue 16", "Zone Industrielle", "Belleville", "France Amérique", "Habitat", "Gare de Bassam"],
                "Adjamé": ["Château d'Eau", "Mirador", "220 Logements", "Siporex", "Renault", "Williamsville", "Habitat Extension", "Paillet"],
                "Abobo": ["Sagbé", "PK18", "N'Dotré", "Avocatier", "Anador", "Houantoué", "Depot", "Belle Ville", "Akeikoi", "Bocabo", "Samaké", "Abobo Baoulé", "Anonkoua Kouté"],
                "Attécoubé": ["Locodjro", "Abobo-Doumé", "Santé", "Agban", "Jean-Paul 2", "Bidjante"],
                "Songon": ["Songon Agban", "Songon Kassemblé", "Songon Dagbé", "Songon M'Brathé", "Abiaté"],
                "Anyama": ["Anyama Centre", "Ebimpé", "Akeikoi", "Cité Belle Ville", "Schneider"]
            },
            "Grand-Bassam": {
                "Grand-Bassam": ["Quartier France", "Moossou", "Impérial", "Phare", "Rosiers", "Mockeyville", "Modeste", "Azuretti", "CAFOP", "Zone Hôtelière"]
            },
            "Assinie": {
                "Assinie": ["Assinie Mafia", "Assinie France", "PK 0 à 10", "PK 10 à 20", "Mandjan", "Essankro"]
            },
            "Bonoua": {
                "Bonoua": ["Centre-Ville", "Begnini", "Bronoukro", "Kumassi"]
            },
            "Dabou": {
                "Dabou": ["Centre-Ville", "Kpass", "Gbougbo", "Debrimou"]
            },
            "Jacqueville": {
                "Jacqueville": ["Bord de Mer", "Centre-Ville", "N'djem", "Sassako", "Abreby"]
            },
            "Yamoussoukro": {
                "Yamoussoukro": ["Morofé", "Habitat", "220 Logements", "Fondation", "N'Gokro", "Koko", "Zaher", "Résidentiel", "Assabou", "Dioulakro", "Kokrenou"]
            },
            "Bouaké": {
                "Bouaké": ["Air France 1", "Air France 2", "Koko", "Nimbo", "Belleville", "Dar-es-Salam", "Kennedy", "Broukro", "Ahougnanssou", "Commerce", "Zone Industrielle", "N'Gattakro"]
            },
            "San-Pédro": {
                "San-Pédro": ["Balmer", "Cité", "Bardot", "Seweke", "Zone Portuaire", "Lac", "Nitoro", "Poro"]
            },
            "Korhogo": {
                "Korhogo": ["Koko", "Soba", "Haoussabougou", "Préfecture", "Sinistré", "Télescope", "Résidentiel"]
            },
            "Daloa": {
                "Daloa": ["Tazibouo", "Lobia", "Gbeuliville", "Kennedy", "Commerce", "Marais"]
            },
            "Man": {
                "Man": ["Lycée", "Grand Gbapleu", "Doyagouiné", "Koko", "Domoraud", "Sari"]
            },
            "Gagnoa": {
                "Gagnoa": ["Babré", "Garahio", "Zapredine", "Dioulabougou", "Rond-Point"]
            },
            "Abengourou": {
                "Abengourou": ["Agni-Mansou", "Relais", "Plateau", "Indénié", "Château"]
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
        const countries = Object.keys(AFRICA_LOCATIONS).sort((a, b) => a.localeCompare(b, 'fr'));
        const ciIdx = countries.indexOf("Côte d'Ivoire");
        if (ciIdx > -1) {
            countries.splice(ciIdx, 1);
            countries.unshift("Côte d'Ivoire");
        }
        return countries;
    }

    function getCities(country) {
        if (!country || !AFRICA_LOCATIONS[country]) {
            // Proposer en priorité les grandes villes de Côte d'Ivoire puis les autres capitales
            let ciCities = AFRICA_LOCATIONS["Côte d'Ivoire"] ? Object.keys(AFRICA_LOCATIONS["Côte d'Ivoire"]) : [];
            let allCities = [];
            Object.keys(AFRICA_LOCATIONS).forEach(c => {
                if (c !== "Côte d'Ivoire") {
                    allCities = allCities.concat(Object.keys(AFRICA_LOCATIONS[c]));
                }
            });
            let sortedOther = Array.from(new Set(allCities)).sort((a, b) => a.localeCompare(b, 'fr'));
            return [...ciCities, ...sortedOther];
        }
        return Object.keys(AFRICA_LOCATIONS[country]).sort((a, b) => a.localeCompare(b, 'fr'));
    }

    function getCommunes(country, city) {
        if (country && AFRICA_LOCATIONS[country]) {
            if (city && AFRICA_LOCATIONS[country][city]) {
                return Object.keys(AFRICA_LOCATIONS[country][city]).sort((a, b) => a.localeCompare(b, 'fr'));
            }
            // Si pas de ville spécifiée dans ce pays, agréger toutes les communes de ce pays
            let cityCommunes = [];
            Object.keys(AFRICA_LOCATIONS[country]).forEach(v => {
                cityCommunes = cityCommunes.concat(Object.keys(AFRICA_LOCATIONS[country][v]));
            });
            return Array.from(new Set(cityCommunes)).sort((a, b) => a.localeCompare(b, 'fr'));
        }
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
        // Par défaut (aucun pays ni ville sélectionné), proposer les communes de Côte d'Ivoire
        let defaultCommunes = [];
        if (AFRICA_LOCATIONS["Côte d'Ivoire"]) {
            Object.keys(AFRICA_LOCATIONS["Côte d'Ivoire"]).forEach(v => {
                defaultCommunes = defaultCommunes.concat(Object.keys(AFRICA_LOCATIONS["Côte d'Ivoire"][v]));
            });
        }
        return Array.from(new Set(defaultCommunes)).sort((a, b) => a.localeCompare(b, 'fr'));
    }

    function getQuartiers(country, city, commune) {
        let quartiers = [];
        // Cas 1 : Pays, Ville, Commune définis
        if (country && city && commune && AFRICA_LOCATIONS[country]?.[city]?.[commune]) {
            return AFRICA_LOCATIONS[country][city][commune];
        }
        // Cas 2 : Recherche par Commune seule ou Ville+Commune
        if (commune || city || country) {
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
        // Par défaut (rien de sélectionné), proposer les quartiers phares de Côte d'Ivoire
        if (AFRICA_LOCATIONS["Côte d'Ivoire"]?.["Abidjan"]) {
            Object.keys(AFRICA_LOCATIONS["Côte d'Ivoire"]["Abidjan"]).forEach(com => {
                quartiers = quartiers.concat(AFRICA_LOCATIONS["Côte d'Ivoire"]["Abidjan"][com]);
            });
        }
        return Array.from(new Set(quartiers)).sort((a, b) => a.localeCompare(b, 'fr'));
    }

    // ── 3. MISE À JOUR POPULATION DES SELECTS ET DATALISTS ───────────────────────

    function populateTarget(elementOrId, items, placeholder) {
        if (!elementOrId) return;
        const el = typeof elementOrId === 'string' ? document.getElementById(elementOrId) : elementOrId;
        if (!el) return;

        if (el.tagName === 'SELECT') {
            const desiredVal = el.getAttribute('data-value') || el.value || '';
            el.innerHTML = '';
            const defaultOpt = document.createElement('option');
            defaultOpt.value = '';
            defaultOpt.textContent = placeholder || 'Tous';
            el.appendChild(defaultOpt);

            items.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item;
                opt.textContent = item;
                if (item === desiredVal) {
                    opt.selected = true;
                }
                el.appendChild(opt);
            });

            if (desiredVal && items.includes(desiredVal)) {
                el.value = desiredVal;
            }
        } else if (el.tagName === 'DATALIST') {
            el.innerHTML = '';
            const fragment = document.createDocumentFragment();
            items.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item;
                fragment.appendChild(opt);
            });
            el.appendChild(fragment);
        }
    }

    // ── 4. ATTACHEMENT DU CASCADING SUR UN GROUPE DE CHAMPS ────────────────────

    function bindCascadingGroup(config) {
        const elPays     = document.querySelector(config.paysSelector);
        const elVille    = document.querySelector(config.villeSelector);
        const elCommune  = document.querySelector(config.communeSelector);
        const elQuartier = document.querySelector(config.quartierSelector);

        if (!elPays && !elVille && !elCommune && !elQuartier) return;

        const targetPays     = (elPays && elPays.tagName === 'SELECT') ? elPays : document.getElementById(config.paysDatalistId);
        const targetVille    = (elVille && elVille.tagName === 'SELECT') ? elVille : document.getElementById(config.villeDatalistId);
        const targetCommune  = (elCommune && elCommune.tagName === 'SELECT') ? elCommune : document.getElementById(config.communeDatalistId);
        const targetQuartier = (elQuartier && elQuartier.tagName === 'SELECT') ? elQuartier : document.getElementById(config.quartierDatalistId);

        // Populate initiales des pays
        populateTarget(targetPays, getCountries(), config.paysPlaceholder || 'Tous les pays');

        function refreshCascade() {
            const currentPays    = elPays ? elPays.value.trim() : '';
            const currentVille   = elVille ? elVille.value.trim() : '';
            const currentCommune = elCommune ? elCommune.value.trim() : '';

            populateTarget(targetVille, getCities(currentPays), config.villePlaceholder || 'Toutes les villes');
            populateTarget(targetCommune, getCommunes(currentPays, currentVille), config.communePlaceholder || 'Toutes les communes');
            populateTarget(targetQuartier, getQuartiers(currentPays, currentVille, currentCommune), config.quartierPlaceholder || 'Tous les quartiers');
        }

        refreshCascade();

        if (elPays) {
            elPays.addEventListener('change', () => {
                if (elVille && elVille.tagName === 'SELECT') { elVille.removeAttribute('data-value'); elVille.value = ''; }
                if (elCommune && elCommune.tagName === 'SELECT') { elCommune.removeAttribute('data-value'); elCommune.value = ''; }
                if (elQuartier && elQuartier.tagName === 'SELECT') { elQuartier.removeAttribute('data-value'); elQuartier.value = ''; }
                refreshCascade();
            });
            if (elPays.tagName !== 'SELECT') elPays.addEventListener('input', refreshCascade);
        }

        if (elVille) {
            elVille.addEventListener('change', () => {
                const currentPays  = elPays ? elPays.value.trim() : '';
                const currentVille = elVille.value.trim();
                if (elCommune && elCommune.tagName === 'SELECT') { elCommune.removeAttribute('data-value'); elCommune.value = ''; }
                if (elQuartier && elQuartier.tagName === 'SELECT') { elQuartier.removeAttribute('data-value'); elQuartier.value = ''; }
                populateTarget(targetCommune, getCommunes(currentPays, currentVille), config.communePlaceholder || 'Toutes les communes');
                populateTarget(targetQuartier, getQuartiers(currentPays, currentVille, elCommune ? elCommune.value.trim() : ''), config.quartierPlaceholder || 'Tous les quartiers');
            });
            if (elVille.tagName !== 'SELECT') elVille.addEventListener('input', refreshCascade);
        }

        if (elCommune) {
            elCommune.addEventListener('change', () => {
                const currentPays    = elPays ? elPays.value.trim() : '';
                const currentVille   = elVille ? elVille.value.trim() : '';
                const currentCommune = elCommune.value.trim();
                if (elQuartier && elQuartier.tagName === 'SELECT') { elQuartier.removeAttribute('data-value'); elQuartier.value = ''; }
                populateTarget(targetQuartier, getQuartiers(currentPays, currentVille, currentCommune), config.quartierPlaceholder || 'Tous les quartiers');
            });
            if (elCommune.tagName !== 'SELECT') elCommune.addEventListener('input', refreshCascade);
        }
    }

    // ── 5. INITIALISATION AUTOMATIQUE SUR LE DOM ───────────────────────────────

    function initAfricaLocations() {
        // A. Formulaire Hero de la page d'accueil (Home)
        bindCascadingGroup({
            paysSelector: '#heroPays',
            paysDatalistId: 'hero-pays',
            paysPlaceholder: 'Tous les pays',
            villeSelector: '#heroVille',
            villeDatalistId: 'hero-villes',
            villePlaceholder: 'Toutes les villes',
            communeSelector: '#heroCommune',
            communeDatalistId: 'hero-communes',
            communePlaceholder: 'Toutes les communes',
            quartierSelector: '#heroQuartier',
            quartierDatalistId: 'hero-quartiers',
            quartierPlaceholder: 'Tous les quartiers'
        });

        // B. Filtres de la page Liste d'Annonces (/annonces)
        bindCascadingGroup({
            paysSelector: '#filterPays',
            paysDatalistId: 'list-pays',
            paysPlaceholder: 'Tous les pays',
            villeSelector: '#filterVille',
            villeDatalistId: 'list-villes',
            villePlaceholder: 'Toutes les villes',
            communeSelector: '#filterCommune',
            communeDatalistId: 'list-communes',
            communePlaceholder: 'Toutes les communes',
            quartierSelector: '#filterQuartier',
            quartierDatalistId: 'list-quartiers',
            quartierPlaceholder: 'Tous les quartiers'
        });

        // C. Formulaire d'administration (/admin/annonces)
        bindCascadingGroup({
            paysSelector: '#adminPays, #addAnnonceModal select[name="pays"], #addAnnonceModal input[name="pays"]',
            paysDatalistId: 'admin-list-pays',
            paysPlaceholder: 'Choisir un pays',
            villeSelector: '#adminVille, #addAnnonceModal select[name="ville"], #addAnnonceModal input[name="ville"]',
            villeDatalistId: 'admin-list-villes',
            villePlaceholder: 'Choisir une ville',
            communeSelector: '#adminCommune, #addAnnonceModal select[name="commune"], #addAnnonceModal input[name="commune"]',
            communeDatalistId: 'admin-list-communes',
            communePlaceholder: 'Choisir une commune',
            quartierSelector: '#adminQuartier, #addAnnonceModal select[name="quartier"], #addAnnonceModal input[name="quartier"]',
            quartierDatalistId: 'admin-list-quartiers',
            quartierPlaceholder: 'Choisir un quartier'
        });

        // D. Formulaire de demande (/demandes)
        bindCascadingGroup({
            paysSelector: '#demandePays',
            paysDatalistId: 'list-pays',
            paysPlaceholder: 'Tous les pays',
            villeSelector: '#demandeVille',
            villeDatalistId: 'list-villes',
            villePlaceholder: 'Toutes les villes',
            communeSelector: '#demandeCommune',
            communeDatalistId: 'list-communes',
            communePlaceholder: 'Toutes les communes',
            quartierSelector: '#demandeQuartier',
            quartierDatalistId: 'list-quartiers',
            quartierPlaceholder: 'Tous les quartiers'
        });

        // E. Formulaire d'inscription (Login/Register)
        const registerCountry = document.querySelector('#tab-register select[name="country"], #tab-register input[name="country"]');
        if (registerCountry) {
            if (registerCountry.tagName === 'SELECT') {
                populateTarget(registerCountry, getCountries(), 'Choisir votre pays');
            } else {
                registerCountry.setAttribute('list', 'list-pays');
            }
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAfricaLocations);
    } else {
        initAfricaLocations();
    }

    // Exposer l'objet globalement pour utilisation personnalisée si besoin
    window.AFRICA_LOCATIONS = AFRICA_LOCATIONS;
    window.bindCascadingGroup = bindCascadingGroup;
})();
