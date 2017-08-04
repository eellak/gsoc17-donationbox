from django.http import HttpResponse
from django.template import loader

from .models import Donation_Project


# Create your views here.


def index(request):
    all_projects = Donation_Project.objects.all()
    template = loader.get_template('presentation/index.html')
    context =\
    {
        'all_projects': all_projects,
    }

    return HttpResponse( template.render(context, request) )


