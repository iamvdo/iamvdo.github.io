title: Article
pages: true
files: true
fields:
  title: 
    label: Title
    type:  text
  text: 
    label: Text
    type:  textarea
    size:  large
  subtitle: 
    label: Subtitle
    type: text
  description: 
    label: Description
    type: text
  date: 
    label: Date
    help: Date au format yyyy-mm-dd h:i
    type: text
  tags:
    label: Tags
    help: Séparés par une virgule
    type: text
  big:
    label: Big
    type: radio
    options:
      0: 0
      1: 1
    default: 0