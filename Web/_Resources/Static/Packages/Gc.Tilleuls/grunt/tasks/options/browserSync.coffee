module.exports =
  dev:
    options:
      watchTask: true
      server:
        baseDir: "../"
    bsFiles:
      src : [
        '<%= in8.cssDest %>/*.css'
        '<%= in8.jsDest %>/*.js'
        '<%= in8.imgSrc %>/**'
      ]
