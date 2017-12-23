const config = module.exports = {
  map: { inline: false },
  plugins: [
    require('postcss-partial-import')(),
    require('postcss-nested')(),
    require('postcss-media-variables')(),
    require('postcss-css-variables')(),
    require('postcss-calc')(),
    require('postcss-media-variables')(),
    require('postcss-media-minmax')(),
    require('rucksack-css')({ autoprefixer: true }),
    require('cssnano')(),
  ]
}
