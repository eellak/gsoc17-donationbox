from django.core import management
from django.core.management.base import BaseCommand, CommandError
from presentation.models import Donation_Project

class Command(BaseCommand):
    help = 'Syncs the local SQLite database, with the remote database.'

    def print_meesage(self):
        self.stdout.write(self.style.SUCCESS("test1"))
        self.stderr.write(self.style.ERROR("test2"))


    def handle(self, *args, **options):
        self.stdout.write( self.style.SUCCESS("OK let's go!") )


if __name__ == "__main__":
    management.call_command('Command')
