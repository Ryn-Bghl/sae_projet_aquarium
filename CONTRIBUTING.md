# Guide de Contribution – SAE Aquarium

Ce document sert de guide pour tous les membres de l'équipe projet. Pour assurer une collaboration efficace et garder notre dépôt propre, merci de suivre les règles ci-dessous.

## 🎯 Principes Clés

1.  **On ne travaille jamais directement sur `main`**.
2.  Chaque nouvelle tâche (fonctionnalité, bug, documentation) se fait sur une **branche dédiée**.
3.  Toutes les modifications doivent être intégrées via une **Pull Request (PR)**.
4.  Une PR doit être **relue et approuvée** par au moins un autre membre de l'équipe avant d'être fusionnée.

---

## 🚀 Workflow de Développement

### 1. Prérequis

*   **Configurez Git** avec votre nom et email si ce n'est pas déjà fait :
    ```bash
    git config --global user.name "Votre Nom"
    git config --global user.email "votre.email@etu.univ-lemans.fr"
    ```
*   **Installez le projet** en suivant les instructions du `README.md`.

### 2. Démarrer une nouvelle tâche

1.  **Synchronisez votre dépôt local** avec la dernière version de `main` :
    ```bash
    git checkout main
    git pull origin main
    ```

2.  **Créez une nouvelle branche** avec un nom explicite en utilisant les préfixes suivants :
    *   `feat/` pour une nouvelle fonctionnalité (ex: `feat/formulaire-connexion`).
    *   `fix/` pour une correction de bug (ex: `fix/erreur-calcul-ph`).
    *   `docs/` pour la documentation (ex: `docs/mise-a-jour-readme`).
    *   `style/` pour les changements d'interface et de style (ex: `style/nouvelle-palette-couleurs`).

    ```bash
    # Exemple pour une nouvelle fonctionnalité
    git checkout -b feat/tableau-de-bord
    ```

### 3. Effectuer et Valider les Modifications

1.  **Développez** votre fonctionnalité sur votre branche.
2.  **Commitez vos changements** régulièrement en utilisant des messages de commit clairs, basés sur le [système de commit conventionnel](https://www.conventionalcommits.org/).

    *   **Format** : `<type>: <description>`
    *   **Exemples** :
        *   `feat: ajouter le graphique de suivi du pH`
        *   `fix: corriger l'affichage des dates sur le tableau de bord`
        *   `docs: ajouter le guide pour la page de connexion`

    ```bash
    git add .
    git commit -m "feat: créer la structure de base du tableau de bord"
    ```

### 4. Partager et Fusionner le Travail

1.  **Poussez votre branche** sur le dépôt distant :
    ```bash
    git push origin feat/tableau-de-bord
    ```

2.  **Ouvrez une Pull Request (PR)** sur GitHub depuis votre branche vers la branche `main`.
    *   Donnez un titre clair à votre PR.
    *   Décrivez les changements que vous avez effectués et ce que le relecteur doit vérifier.
    *   **Assignez un ou plusieurs membres de l'équipe** pour la relecture.

3.  **Revue de code et fusion** :
    *   L'équipe relit le code, laisse des commentaires si nécessaire.
    *   L'auteur de la PR applique les corrections demandées.
    *   Une fois la PR approuvée, elle peut être fusionnée dans `main`.

---

Merci de suivre ce guide pour assurer le succès de notre projet ! 🎉
