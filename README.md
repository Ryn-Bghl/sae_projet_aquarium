# 🌊 SAE 301/303 – Projet Aquarium

**Département MMI x Département Génie Biologique – 2025-2026**

Application web développée dans le cadre du BUT MMI – SAE 301/303, en collaboration avec les étudiants en Génie Biologique.
Elle propose un **tableau de bord interactif** pour gérer, saisir et visualiser les données des aquariums pédagogiques.

---

## 📖 Contexte du projet

Ce projet est issu de la nouvelle collaboration entre le **Département de Génie Biologique** et le **Département MMI**.
L’objectif est de mettre à disposition des étudiants GB un **outil numérique visuel et pratique** pour faciliter le suivi de leurs aquariums dans le cadre des **SAE**.

### 🎯 Objectifs

* Centraliser les **données physico-chimiques et biologiques** des aquariums.
* Suivre et représenter le **cycle de l’azote** :

  * Ammoniaque (NH₃) → Nitrites (NO₂⁻) → Nitrates (NO₃⁻).
* Offrir des **estimations et rappels automatiques** :

  * cycle de l’azote,
  * nourriture,
  * changement d’eau.
* Guider les étudiants dans la **réflexion sur l’aquarium/terrarium** :

  * quels animaux ?
  * quelles plantes ?
  * quelle eau ?
  * quel format ?
* Personnalisation possible (paramètres, thèmes).

### 📊 Paramètres suivis

* Température (°C)
* pH
* GH (dureté totale → calcium + magnésium)
* KH (dureté carbonatée → stabilité du pH)
* Nitrites (NO₂⁻)
* Chlore (Cl⁻)
* Carbonates (CO₃²⁻)

### 📅 Organisation & jalons

* **23 septembre** : rencontre GB–MMI, création du cahier des charges.
* **24 novembre** : point d’avancement, présentation de l’application et envoi des fichiers de test.
* **27 janvier** : soutenance finale et vote des étudiants GB pour élire l’application retenue.

---

## 🛠️ Compétences mobilisées

* **Gestion de projet** : planification, suivi, communication.
* **Développement backend** :

  * PHP (architecture MVC),
  * CRUD (Create, Read, Update, Delete),
  * Programmation orientée objets,
  * Gestion et lecture de fichiers.
* **Développement frontend** :

  * HTML5, CSS3, JavaScript,
  * Responsive design,
  * Visualisation de données (D3.js).
* **UX / Accessibilité** : interface claire, adaptée aux besoins pédagogiques.

---

## 📂 Structure du dépôt (MVC PHP)

```
📦 sae_projet_aquarium
┣ 📂 src                        # Code source principal
┃ ┣ 📂 app                      # Application MVC
┃ ┃ ┣ 📂 controller             # Contrôleurs (logique métier)
┃ ┃ ┣ 📂 model                  # Modèles (accès aux données)
┃ ┃ ┗ 📂 view                   # Vues (interfaces utilisateur)
┃ ┃   ┗ 📂 common               # Composants communs (Layout, Header, Footer)
┃ ┗ 📂 public                   # Fichiers publics (CSS, JS, images)
┣ 📂 database                   # Scripts SQL et structure BDD
┣ 📂 docs                       # Documentation du projet
┃ ┗ 📂 livrables                # Documents livrables
┣ 📄 README.md                  # Documentation principale
┗ 📄 LICENCE                    # Licence d'utilisation
```

---

## 🗂️ Livrables attendus

* Cahier des charges.
* Maquettes et prototypes.
* Structure de la base de données.
* Application web fonctionnelle (hébergée sur les serveurs du département).
* Rapport explicatif du travail réalisé.

---

## 🛣️ Roadmap du projet

* **Phase 1 (septembre)** : Rencontre avec GB, définition du cahier des charges.
* **Phase 2 (octobre)** : Conception (maquettes, structure BDD, choix techniques).
* **Phase 3 (novembre)** : Développement initial + point d’avancement avec GB.
* **Phase 4 (décembre)** : Ajout des fonctionnalités avancées (visualisations, rappels).
* **Phase 5 (janvier)** : Finalisation, tests avec données réelles, déploiement.
* **Phase 6 (27 janvier)** : Soutenance et présentation finale.

---

## 📜 Licence

Ce projet est distribué sous la licence MIT. Vous êtes libre de l’utiliser, le modifier et le redistribuer, sous réserve de conserver la mention d’attribution.
