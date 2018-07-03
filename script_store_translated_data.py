#!/usr/bin/env python3

"""
Script to select sentences by active learning
"""

from datetime import datetime

from sqlalchemy import create_engine
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import sessionmaker

from sqlalchemy import ForeignKey
from sqlalchemy.orm import relationship
from sqlalchemy import Column, Integer, String, Float
from sqlalchemy import Table

from gensim.models.wrappers import FastText
import re
import spacy

nlp = spacy.load('es')

engine = create_engine("sqlite:///database.db")
Session = sessionmaker(bind=engine)
Base = declarative_base()
Base.metadata.create_all(engine)
session = Session()

class Document(Base):
    __tablename__ = 'documents'

    id = Column(Integer, primary_key=True)
    title = Column(String)
    user = Column(Integer, ForeignKey('users.id'))
    sl = Column(String) ## string?
    sentences = relationship("Sentence")

    def __init__(self, title, user, sl):
        self.title = title
        self.user = user
        self.sl = sl

    def __repr__(self):
       return ("<Document(%d, '%s', '%s', '%s')>"
                % (self.id, self.title, self.user, self.sl))

class Sentence(Base):
    __tablename__ = 'sentences'

    id = Column(Integer, primary_key=True)
    docid = Column(Integer, ForeignKey('documents.id'))
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

    def __init__(self, text, translation, docid):
        self.text  = text
        self.docid = docid

    def __repr__(self):
       return ("<Sentence(%d, '%s', '%s', <Meta('oovw': %d, 'rep': %d, 'adj': %d, 'noun': %d, 'verb': %d))>" % (self.id, self.text, self.translation, self._oovw, self._repetitions, self._POS_adj, self._POS_noun, self._POS_verb))

def calculate_pos(sentence):
    score = 0
    doc = nlp(sentence.text)
    sentence._POS_adj   = sum(1 for word in doc if word.pos_.startswith('ADJ'))
    sentence._POS_adv   = sum(1 for word in doc if word.pos_.startswith('ADV'))
    sentence._POS_noun  = sum(1 for word in doc if word.pos_.startswith('NOUN'))
    sentence._POS_propn = sum(1 for word in doc if word.pos_.startswith('PROPN'))
    sentence._POS_verb  = sum(1 for word in doc if word.pos_.startswith('VERB'))
    sentence._POS_intj  = sum(1 for word in doc if word.pos_.startswith('INTJ'))

    return sentence


def add_document(session):
    document = Document("Corpus Paralelo Educativo", "IA-PUCP", "es")
    session.add(document)
    session.commit()
    print("added document:", document)
    return document

def storeTranslations(docid):
    es_file = open('es-shp/educativo-train.es', 'r')
    shp_file = open('es-shp/educativo-train.shp', 'r')
    sentences = []

    for es_line in es_file:
        shp_line = shp_file.readline()
        sentence = Sentence(es_line, shp_line, docid)
        session.add(sentence)
        sentences.append(sentence)
        print("added sentence:", sentence)

def main():
    # store text and translation
    print('store text and translation')
    document = add_document()
    sentences = storeTranslations(document.id)
    session.commit()

    # store meta
    print('store POS meta')
    for sentence in sentences:
        sentence = calculate_pos(sentence)
        session.commit()
        print(sentence)

if __name__ == "__main__": main()

