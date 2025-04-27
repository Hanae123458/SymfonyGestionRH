const Encore = require('@symfony/webpack-encore');

// Vérifie si Webpack Encore est configuré
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment('dev'); // ou 'prod' selon l'environnement que tu utilises
}

Encore
    // Le répertoire où seront placés les fichiers compilés
    .setOutputPath('public/build/')
    // Le répertoire public où Webpack va générer les fichiers
    .setPublicPath('/build')

    // Ajoute une entrée pour ton fichier JavaScript principal
    .addEntry('app', './assets/app.js') // Assure-toi que ce fichier existe dans ton projet

    // Active le loader pour les fichiers CSS et SCSS
    .enableSassLoader()

    // Pour la gestion des fichiers JS
    .enableReactPreset()

    // Active le `runtime chunk` unique pour un meilleur cache
    .enableSingleRuntimeChunk()

    // Active le minification pour le mode de production
    .enableVersioning(false) // Si tu veux activer la version des fichiers, mets-le sur `true`

    // Active le support pour le CSS avec Tailwind
    .enablePostCssLoader()
;

module.exports = Encore.getWebpackConfig();
