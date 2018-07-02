#!/usr/bin/env python3

"""
Script to test sentence meta data anotation
"""

import argparse
import os

import csv

from sqlalchemy import create_engine
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import sessionmaker
from sqlalchemy import Column, Integer, String, Float
from sqlalchemy import Table

from functools import reduce
import re

import spacy

nlp = spacy.load('es')

engine = create_engine("sqlite:///database.db")
Session = sessionmaker(bind=engine)
Base = declarative_base()
Base.metadata.create_all(engine)
session = Session()

class Sentence(Base):
    __tablename__ = 'sentences'

    id = Column(Integer, primary_key=True)
    text = Column(String)
    translation = Column(String)
    score = Column(Float)

    # meta data
    _oovw = Column(Integer)
    _words = Column(Integer)
    _repetitions = Column(Integer)

    _POS_adj   = Column(Integer)
    _POS_adv   = Column(Integer)
    _POS_noun  = Column(Integer)
    _POS_propn = Column(Integer)
    _POS_verb  = Column(Integer)
    _POS_intj  = Column(Integer) # interjection: an exclamation or part of an exclamation

    def __init__(self, text, docid):
        self.text  = text
        self.docid = docid

    def __repr__(self):
       return ("<Sentence(%d, '%s', '%s', <Meta('oovw': %d, 'rep': %d, 'adj': %d, 'noun': %d, 'verb': %d))>" % (self.id, self.text, self.translation, self._oovw, self._repetitions, self._POS_adj, self._POS_noun, self._POS_verb))

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
	sentence._repetitions = repetitions

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
	sentences = session.query(Sentence).filter(Sentence._words == None).all()
	corpus_es = []

	for sentence in sentences:
		sentence = calculate_oovw(sentence, corpus_es)
		sentence = calculate_pos(sentence, corpus_es)
		session.commit()
		print(sentence)

if __name__ == "__main__": main()
