#!/usr/bin/python3
import sys
from scale import Scale

scale = Scale()
scale.setReferenceUnit(1)
scale.reset()
scale.tare()

first_measure = scale.getMeasure()
print ('first mesaured value:')
print (first_measure)
input('Bitte Gewicht anhaengen')
second_measure = scale.getMeasure()
print ('second mesaured value: ')
print (second_measure)
referenceUnit = second_measure - first_measure
weight = int(input('angehaengtes Gewicht? '))
referenceUnit = referenceUnit // weight
print ('Reference Unit: ')
print (referenceUnit)