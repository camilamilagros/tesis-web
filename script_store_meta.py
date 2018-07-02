#!/usr/bin/env python3

"""
Script to test sentence meta data anotation
"""

import argparse
import os

import model
import sqlalchemy
import csv
import fasttext

from sqlalchemy import create_engine
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import sessionmaker

from model import Sentence
from functools import reduce
import re

import spacy

nlp = spacy.load('es')

# calculates number of oovw in sentence
def calculate_oovw(sentence, corpus_es):
	score = 0
	repetitions = 0
	for word in sentence.text.split():
		count = 0
		for corpus_line in corpus_es:
			count += len(re.findall(word, corpus_line))
		repetitions += count
		if count < 1:
			score += 1

	sentence._oovw = score
	sentence._words = len(sentence.text.split())
	sentence._repetitions = repetitions/sentence._words

	return sentence

def calculate_pos(sentence, corpus_es):
	score = 0
	doc = nlp(sentence.text)
	sentence._POS_adj   = sum(1 for word in doc if word.pos_.startswith('ADJ'))
	sentence._POS_adv   = sum(1 for word in doc if word.pos_.startswith('ADV'))
	sentence._POS_noun  = sum(1 for word in doc if word.pos_.startswith('NOUN'))
	sentence._POS_propn = sum(1 for word in doc if word.pos_.startswith('PROPN'))
	sentence._POS_verb  = sum(1 for word in doc if word.pos_.startswith('VERB'))
	sentence._POS_intj  = sum(1 for word in doc if word.pos_.startswith('INTJ'))

	return sentence

def main():
	engine = create_engine("sqlite:///guampa.db")
	Session = sessionmaker(bind=engine)
	Base = model.Base
	Base.metadata.create_all(engine)
	session = Session()

	sentences = session.query(Sentence).take(5).all()
	corpus_es = []
	print('Loop c:')

	for sentence in sentences:
		sentence = calculate_oovw(sentence, corpus_es)
		sentence = calculate_pos(sentence, corpus_es)
		session.commit()
		print(sentence)

if __name__ == "__main__": main()
