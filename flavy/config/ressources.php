<?php

return [
    'sante_physique' => [
        'nom' => "Problème de santé physique",
        'items' => [
            [
                'nom' => 'Info-Santé 811',
                'tel' => '811',
                'site' => 'https://www.quebec.ca/sante/trouver-une-ressource/info-sante-811',
                'desc' => "Service gratuit disponible 24h/24 où tu peux parler à une infirmière pour des
                problèmes de santé non urgents. Elle peut te donner des conseils, te dire quoi faire et te diriger vers la bonne ressource.",
                ],
            [
                'nom' => 'CLSC',
                'tel' => 'Variable',
                'site' => 'https://www.quebec.ca/sante/trouver-une-ressource/clsc',
                'desc' => 'Les CLSC offrent des soins de santé de base (infirmières, vaccins, suivi médical,
                 services sociaux). Ils peuvent aussi intervenir à domicile ou dans la communauté.
                 C’est souvent le premier endroit où aller pour obtenir de l’aide.',
                ],
            [
                'nom' => 'Pharmacies (ex. Jean Coutu, Pharmaprix)',
                'tel' => 'Variable',
                'site' => 'Variable',
                'desc' => 'Conseils de santé, médicaments et parfois consultations avec un pharmacien.',
                ],
            [
                'nom' => 'Guichet d’accès à la première ligne (GAP)',
                'tel' => '811 (option 3)',
                'site' => 'https://gap.soinsvirtuels.gouv.qc.ca',
                'desc' => 'Service pour les personnes sans médecin de famille.
                 Il permet d’être orienté vers un professionnel de la santé (médecin, infirmière, pharmacien) selon ton besoin.',
                ],
            [
                'nom' => 'Québec.ca (portail santé)',
                'tel' => 'Aucun',
                'site' => 'https://www.quebec.ca/sante',
                'desc' => 'Site officiel regroupant toutes les informations et ressources en santé au Québec.',
                ],
            ]
    ],

    'sante_mentale' => [
        'nom' => "Problème de santé mentale",
        'items' => [
            [
                'nom' => 'Info-Social 811',
                'tel' => '811 option 2',
                'site' => 'https://www.quebec.ca/sante/trouver-une-ressource/info-social-811',
                'desc' => 'Service d’écoute avec des intervenants psychosociaux pour des problèmes comme l’anxiété,
                 la dépression, les conflits ou le stress. Disponible en tout temps et accessible à toute la population.',
                ],
            [
                'nom' => 'Tel-Aide',
                'tel' => '514-935-1101',
                'site' => 'https://telaide.org',
                'desc' => 'Écoute anonyme 24/7',
                ],
            [
                'nom' => 'Revivre',
                'tel' => '1-866-738-4873',
                'site' => 'https://revivre.org',
                'desc' => 'Soutien anxiété / dépression',
                ],
             [
                'nom' => 'Centre de crise de Québec',
                'tel' => '418-683-4588',
                'site' => 'https://www.centredecrisedequebec.com',
                'desc' => 'Soutien en cas de crise aiguë',
                 ],
            [
                'nom' => 'Relief',
                'tel' => '1-866-738-4873',
                'site' => 'https://www.centredecrisedequebec.com',
                'desc' => 'Description : Organisme offrant entraide et outils pour mieux vivre avec anxiété,
                dépression, bipolarité ou tout autres problème de santé mentale.',
                ],
            [
                'nom' => 'Jeunesse, J’écoute',
                'tel' => '1-800-668-6868',
                'site' => 'https://jeunessejecoute.ca',
                'desc' => 'Aide pour les jeunes partout au Canada concernant la santé mentale et les émotions.',
                ],
            [
                'nom' => 'Ordre des psychologues du Québec',
                'tel' => '514-738-1881',
                'site' => 'https://www.ordrepsy.qc.ca',
                'desc' => 'Permet de trouver un psychologue qualifié et obtenir de l’information.',
                ],
            ]
    ],
    'sante_jeunes-famille' => [
        'nom' => "Problème de santé chez les jeunes et les familles",
        'items' => [
            [
                'nom' => 'Tel-jeunes',
                'tel' => '1-800-263-2266',
                'site' => 'https://teljeunes.com',
                'desc' => 'Aide confidentielle jeunes',
            ],
            [
                'nom' => 'LigneParents',
                'tel' => '1-800-361-5085',
                'site' => 'https://ligneparents.com',
                'desc' => 'Aide pour parents',
            ],
            [
                'nom' => 'Maisons des jeunes',
                'tel' => 'Variable',
                'site' => 'https://rmjq.org',
                'desc' => 'Encadrement et soutien',
                ],
            ]
    ],
    'dependance' => [
        'nom' => "Dépendance",
        'items' => [
            [
                'nom' => "J’arrête",
                'tel' => '1-866-527-7383',
                'site' => 'https://jarrete.qc.ca',
                'desc' => 'Aide pour arrêter de fumer',
                ],
            [
                'nom' => "Alcooliques anonymes",
                'tel' => 'Variable',
                'site' => 'https://aa-quebec.org',
                'desc' => 'Description : Groupes de soutien pour personnes ayant des problèmes d’alcool.',
                ],
            ]
    ],
    'specialisée' => [
        'nom' => "Aide spécialisée et communautaire",
        'items' => [
            [
                'nom' => 'Diabète Québec',
                'tel' => '1-800-361-3504',
                'site' => 'https://www.diabete.qc.ca',
                'desc' => 'Information et soutien pour les personnes atteintes de diabète.',
            ],
            [
                'nom' => 'Société canadienne du cancer',
                'tel' => '1-888-939-3333',
                'site' => 'https://cancer.ca',
                'desc' => 'Ressources et soutien pour les personnes touchées par le cancer.',
            ],
            [
                'nom' => 'Fondation des maladies du cœur',
                'tel' => '1-888-473-4636',
                'site' => 'https://coeuretavc.ca',
                'desc' => 'Information sur les maladies cardiaques et prévention.',
            ],
            [
                'nom' => 'Société de la SLA du Québec',
                'tel' => '514-725-2653 (ou variable selon service)',
                'site' => 'https://sla-quebec.ca',
                'desc' => 'Service pour les personnes sans médecin de famille.
                 Organisme qui aide les personnes atteintes de maladies neurologiques comme la SLA. Offre du soutien,
                  de l’information et des ressources adaptées aux patients et à leurs proches.',
            ],
            [
                'nom' => 'Parkinson Québec',
                'tel' => '1-800-720-1307',
                'site' => 'https://parkinsonquebec.ca',
                'desc' => 'Service pour les personnes sans médecin de famille.
                 Soutien aux personnes atteintes de Parkinson.',
            ],
            [
                'nom' => 'Alzheimer Québec',
                'tel' => '1-888-636-6473',
                'site' => 'https://alzheimer.ca/qc',
                'desc' => 'Service pour les personnes sans médecin de famille.
                 Ressources pour les personnes atteintes et leurs proches.',
            ],
        ]
    ],
    'autres' => [
        'nom' => "Autres",
        'items' => [
            [
                'nom' => 'Centres de femmes',
                'tel' => 'Aucun',
                'site' => 'https://rcentres.qc.ca',
                'desc' => 'Soutien global santé',
                ],
            [
                'nom' => 'SOS violence conjugale',
                'tel' => '1-800-363-9010',
                'site' => 'https://sosviolenceconjugale.ca',
                'desc' => 'Ligne d’aide 24/7 pour les personnes vivant de la violence conjugale.',
                ],
            [
                'nom' => 'Info-aide violence sexuelle',
                'tel' => '1-888-933-9007',
                'site' => 'https://infoaideviolencesexuelle.ca',
                'desc' => 'Soutien confidentiel pour victimes de violence sexuelle.',
                ],
            ]
    ],
];
