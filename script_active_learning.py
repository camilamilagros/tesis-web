#!/usr/bin/env python3

"""
Script to select sentences by active learning
"""

from datetime import datetime

from sqlalchemy import create_engine
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import sessionmaker

from sqlalchemy import Column, Integer, String, Float
from sqlalchemy import Table

from gensim.models.wrappers import FastText
import re
# import spacy

print(str(datetime.now()))
# nlp = spacy.load('es')
w = 0.33333
similarity_threshold = 0.7

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

    def __init__(self, text, translation, docid):
        self.text  = text
        self.docid = docid

    def __repr__(self):
       return ("<Sentence(%d, '%s', '%s', <Meta('oovw': %d, 'rep': %d, 'adj': %d, 'noun': %d, 'verb': %d))>" % (self.id, self.text, self.translation, self._oovw, self._repetitions, self._POS_adj, self._POS_noun, self._POS_verb))

def getCorpusData():
    print(str(datetime.now()) + ' getCorpusData')

    corpus = session.query(Sentence)\
        .filter(Sentence.translation != None)\
        .all()
    POS_acc = {
        'adj' : 0,
        'adv' : 0,
        'noun' : 0,
        'verb' : 0,
        'intj' :0,
        'propn' : 0,
    }
    total_words = 0
    words = []

    for corpus_sentence in corpus:
        words += corpus_sentence.text.split()
        for pos, accumulate in POS_acc.items():
            count = getattr(corpus_sentence, '_POS_' + pos)
            POS_acc[pos] += count
            total_words += count

    POS_weights = {}
    for pos, accumulate in POS_acc.items():
        presence = 0
        if total_words > 0:
            presence = accumulate/total_words
        POS_weights[pos] = 1 - presence

    return words, POS_weights

def evaluatePool(corpus_words, pool, POS_weights):
    print(str(datetime.now()) + ' start load_fasttext_format')
    embedding = FastText.load_fasttext_format('embedding.bin', encoding='utf-8')
    print(str(datetime.now()) + ' finish load_fasttext_format')

    for sentence in pool:
        # lexic
        n = sentence._words
        if n == 0:
            continue;
        oovw = (sentence._oovw / n)
        rep = 1 / (1 + sentence._repetitions / n)
        lexic = (oovw + rep) / 2

        # gramatic
        gramatic = 0
        for pos, pos_weight in POS_weights.items():
            gramatic += pos_weight * (getattr(sentence,'_POS_' + pos)/sentence._words)
        gramatic = gramatic / len(POS_weights)
        # print('lexic: ' + str(lexic) + ', gramatic: ' + str(gramatic))

        # semantics
        synonyms = 0
        for pool_word in sentence.text.split():
            for corpus_word in corpus_words:
                try:
                    synonyms += bool(embedding.similarity(pool_word, corpus_word) >= similarity_threshold)
                except:
                    pass
        semantic = 1 - (n / (n + synonyms))

        sentence.score = w * lexic + w * gramatic + w * semantic
        session.commit()

def main():
    corpus, POS_weights = getCorpusData()
    print(str(datetime.now()) + ' getPool')
    pool = session.query(Sentence)\
        .filter(Sentence.translation == None)\
        .filter(Sentence._words != None)\
        .all()

    print(str(datetime.now()) + ' evaluatePool')
    evaluatePool(corpus, pool, POS_weights)
    print(str(datetime.now()) + ' end!')

if __name__ == "__main__": main()
