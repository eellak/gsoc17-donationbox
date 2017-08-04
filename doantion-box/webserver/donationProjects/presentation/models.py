from django.db import models

# Create your models here.

class Donation_Project( models.Model ):
    title                   = models.CharField( max_length=200 )
    text                    = models.TextField()
    current_amount          = models.PositiveIntegerField()
    target_amount           = models.PositiveIntegerField()
    video_URL               = models.FilePathField( max_length = 200, recursive = True, null = True, path = '/home/donatonProject_files/videos/', match = '*.mp4$' )
    image_URL               = models.FilePathField( max_length = 200, recursive = True, null = True, path = '/home/donatonProject_files/images/' )
    stylesheet_URL          = models.FilePathField( max_length = 200, recursive = True, null = True, path = '/home/donatonProject_files/css/', match = '*.css$' )
    start_date              = models.DateField()
    end_date                = models.DateField()
    last_updated_datetime   = models.DateTimeField( auto_now = True )

    # Κάθε φορά που ενημερώνετε κάποιο πεδίο μιας εγγραφής της βάσης δεδομένων το πεδίο last_updated_datetime,
    # θα αποκτά αυτόματα την τρέχουσα timestamp ( date & time ). :) Useful ε ?
    # Το αγαπητό Django δημιουργεί *αυτόματα* & ένα ακόμη πεδίο ονοματι "id", έτσι γιατί με αγαπά & με διευκολύνει!
    # Προσοχή! Τα πεδία : "video_URL", "image_URL" και "stylesheet_URL", μπορεί να είναι κενά, αλλά δε μπορεί να είναι άδεια!
    # Δηλαδή, αν είναι null (κενά), πάει να πει πως δεν έχουν καν τιμή και όχι πως μπορεί να έχουν μηδενική τιμη ( "" ).


    def __str__(self):
        return self.title
