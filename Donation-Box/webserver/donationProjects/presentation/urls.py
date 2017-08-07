from django.conf.urls import url
from . import views

app_name = 'presentation'
urlpatterns = [
    # http://127.0.0.1:8000/
    url(r'^$', views.index, name='index'),
    # http://127.0.0.1:8000/project/x
    url(r'^project/(?P<project_id>[0-9]+)/$', views.project, name='project')
]