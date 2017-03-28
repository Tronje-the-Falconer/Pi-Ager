#!/usr/bin/python3
import sys
from scale import Scale

scale = Scale()

scale.reset()
first_measure = scale.getMeasure()
print ('first mesaured value:')
print (first_measure)
input('Bitte Gewicht anhängen')
second_measure = scale.getMeasure()
print ('second mesaured value: ')
print (second_measure)
referenceUnit = second_measure - first_measure
print ('Reference Unit: ')
print (referenceUnit)
