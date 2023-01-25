module.exports = {
    // proxy API requests to Valet during development

    devServer: {
        proxy: 'http://dna.local/dna_gest_com_tsaravidy/dna_gest_com_tsaravidy/'
    },
    publicPath: '/dna_gest_com_tsaravidy/dna_gest_com_tsaravidy/public',


    // devServer: {
    //     proxy: ''
    // },
    // publicPath: '/public',

    // output built static files to Laravel's public dir.
    // note the "build" script in package.json needs to be modified as well.
    outputDir: '../public',

    // modify the location of the generated HTML file.
    // make sure to do this only in production.  
    indexPath: process.env.NODE_ENV === 'production' ?
        '../application/modules/main/views/index.php' : 'index.html',
    css: {
        requireModuleExtension: false
    },
    // Parametre langue
    pluginOptions: {
        i18n: {
            locale: 'fr',
            fallbackLocale: 'fr',
            localeDir: 'locales',
            enableInSFC: true
        }
    }
}