<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sentence extends Model
{
	const CRITERIA_WEIGHT = 0.3;
	const SYN_THRESHOLD = 0.7;

	static function selectWithActiveLearning()
	{
		$corpus = self::whereIsNull('translation')
			->take(10)
			->get()
			->map(function ($item, $key) {
				return explode(' ', $item->text);
			});
		$corpus = array_flatten($corpus);
		$pool = self::whereNull('translation')->get();

		foreach ($sentence as $key => $pool) {
			// lexic
			n = sentence['_words']
			oovw = (sentence['_oovw'] / n)
			rep = 1 / (1 + sentence['_repetitions'] / n)
			lexic = (oovw + rep) /
		}

		// for sentence in pool:
		// 	print("Calculate score of " + sentence['text'] + "!")
		// 	# lexic
		// 	n = sentence['_words']
		// 	oovw = (sentence['_oovw'] / n)
		// 	rep = 1 / (1 + sentence['_repetitions'] / n)
		// 	lexic = (oovw + rep) / 2

		// 	# gramatic
		// 	gramatic = 0
		// 	for pos, pos_weight in POS_weights.items():
		// 		gramatic += pos_weight * (sentence['_POS_' + pos]/sentence['_words'])
		// 	gramatic = gramatic / len(POS_weights)

		// 	# semantics
		// 	synonyms = 0
		// 	for pool_word in sentence['text'].split():
		// 		for corpus_word in corpus_words:
		// 			try:
		// 				similar_text($var_1, $var_2, $percent);
		// 				synonyms += bool(embedding.similarity(pool_word, corpus_word) >= similarity_threshold)
		// 			except:
		// 				pass
		// 	semantic = 1 - (n / (n + synonyms))

		// 	sentence['score'] = w * lexic + w * gramatic + w * semantic
		// 	print(sentence['score'])

		// return pool

	}
}
