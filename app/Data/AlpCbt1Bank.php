<?php

namespace App\Data;

/**
 * ─────────────────────────────────────────────────────────────────────────────
 *  RRB ALP — CBT 1 question bank (bilingual: English / हिन्दी)
 * ─────────────────────────────────────────────────────────────────────────────
 *  Pattern (2026 revised): 75 questions · 75 marks · 60 minutes · −1/3 per wrong
 *  Sections: Mathematics, General Intelligence & Reasoning, General Science.
 *  General Awareness has been dropped from CBT 1. CBT 1 is screening-only —
 *  its marks do not count towards the final merit.
 *
 *  Shape of one question:
 *      [
 *        's'  => 'math'|'reasoning'|'science',   // section key
 *        'en' => ['q' => '…', 'o' => ['…','…','…','…']],
 *        'hi' => ['q' => '…', 'o' => ['…','…','…','…']],
 *        'a'  => 0..3,                            // correct option index
 *      ]
 *
 *  IMPORTANT — the 'a' key never leaves this file.
 *  The browser is fed by forPlayer(), which strips it. Scoring happens in
 *  AlpCbt1Controller::submit() using answerKey(). A student cannot read the
 *  answers out of the page source.
 *
 *  Section counts are derived from the data (see sectionCounts()), never
 *  hard-coded — add or remove a question and every screen follows along.
 * ─────────────────────────────────────────────────────────────────────────────
 */
class AlpCbt1Bank
{
    /** Total exam time in minutes. */
    public const DURATION_MINUTES = 60;

    /** Marks awarded per correct answer. */
    public const MARK_CORRECT = 1.0;

    /** Marks deducted per wrong answer (1/3). */
    public const MARK_NEGATIVE = 1 / 3;

    /** Minimum qualifying percentage by category. */
    public const QUALIFYING = [
        'UR / EWS'  => 40,
        'OBC (NCL)' => 30,
        'SC'        => 30,
        'ST'        => 25,
    ];

    /** Section metadata — order here is the order of the tabs on screen. */
    public const SECTIONS = [
        'math' => [
            'en'    => 'Mathematics',
            'hi'    => 'गणित',
            'short' => 'Maths',
            'color' => '#1677F1',
        ],
        'reasoning' => [
            'en'    => 'General Intelligence & Reasoning',
            'hi'    => 'सामान्य बुद्धिमत्ता एवं तर्कशक्ति',
            'short' => 'Reasoning',
            'color' => '#7C4DFF',
        ],
        'science' => [
            'en'    => 'General Science',
            'hi'    => 'सामान्य विज्ञान',
            'short' => 'Science',
            'color' => '#08A899',
        ],
    ];

    /**
     * Every question, answer key included. Server-side only.
     *
     * @return array<int, array{s:string, en:array{q:string,o:array<int,string>}, hi:array{q:string,o:array<int,string>}, a:int}>
     */
    public static function all(): array
    {
        return array_merge(self::mathematics(), self::reasoning(), self::science());
    }

    /**
     * The same questions with the answer key removed — this is what gets
     * json_encode()d into the test page.
     */
    public static function forPlayer(): array
    {
        $out = [];

        foreach (self::all() as $i => $q) {
            $out[] = [
                'n'  => $i + 1,          // 1-based question number
                's'  => $q['s'],
                'en' => $q['en'],
                'hi' => $q['hi'],
            ];
        }

        return $out;
    }

    /** @return array<int,int> question index => correct option index */
    public static function answerKey(): array
    {
        return array_map(static fn (array $q): int => $q['a'], self::all());
    }

    /** @return array<string,int> section key => number of questions */
    public static function sectionCounts(): array
    {
        $counts = array_fill_keys(array_keys(self::SECTIONS), 0);

        foreach (self::all() as $q) {
            $counts[$q['s']]++;
        }

        return $counts;
    }

    /**
     * Question numbers grouped by section, e.g. ['math' => [1,2,…,25], …].
     * Drives the section tabs and the question palette.
     *
     * @return array<string, array<int,int>>
     */
    public static function sectionRanges(): array
    {
        $ranges = array_fill_keys(array_keys(self::SECTIONS), []);

        foreach (self::all() as $i => $q) {
            $ranges[$q['s']][] = $i + 1;
        }

        return $ranges;
    }

    public static function total(): int
    {
        return count(self::all());
    }

    /* ═══════════════════════════════════════════════════════════════════════
       Mathematics — 25 questions
       ═══════════════════════════════════════════════════════════════════════ */
    private static function mathematics(): array
    {
        return [
            [
                's'  => 'math',
                'en' => ['q' => 'What is the HCF of 24 and 36?', 'o' => ['6', '8', '12', '18']],
                'hi' => ['q' => '24 और 36 का महत्तम समापवर्तक (HCF) क्या है?', 'o' => ['6', '8', '12', '18']],
                'a'  => 2,
            ],
            [
                's'  => 'math',
                'en' => ['q' => 'The LCM of 12, 15 and 20 is:', 'o' => ['30', '60', '120', '180']],
                'hi' => ['q' => '12, 15 और 20 का लघुत्तम समापवर्त्य (LCM) है:', 'o' => ['30', '60', '120', '180']],
                'a'  => 1,
            ],
            [
                's'  => 'math',
                'en' => ['q' => 'A train 150 m long crosses a pole in 15 seconds. Its speed is:', 'o' => ['36 km/h', '10 km/h', '25 km/h', '40 km/h']],
                'hi' => ['q' => '150 मीटर लंबी एक रेलगाड़ी एक खंभे को 15 सेकंड में पार करती है। इसकी चाल है:', 'o' => ['36 किमी/घंटा', '10 किमी/घंटा', '25 किमी/घंटा', '40 किमी/घंटा']],
                'a'  => 0,
            ],
            [
                's'  => 'math',
                'en' => ['q' => 'The ratio of the ages of A and B is 3 : 5 and the sum of their ages is 48 years. Find the age of B.', 'o' => ['18 years', '28 years', '30 years', '32 years']],
                'hi' => ['q' => 'A और B की आयु का अनुपात 3 : 5 है तथा उनकी आयु का योग 48 वर्ष है। B की आयु ज्ञात कीजिए।', 'o' => ['18 वर्ष', '28 वर्ष', '30 वर्ष', '32 वर्ष']],
                'a'  => 2,
            ],
            [
                's'  => 'math',
                'en' => ['q' => 'A shopkeeper buys an item for ₹400 and sells it for ₹500. His profit percentage is:', 'o' => ['20%', '25%', '30%', '15%']],
                'hi' => ['q' => 'एक दुकानदार एक वस्तु ₹400 में खरीदकर ₹500 में बेचता है। उसका लाभ प्रतिशत है:', 'o' => ['20%', '25%', '30%', '15%']],
                'a'  => 1,
            ],
            [
                's'  => 'math',
                'en' => ['q' => 'The simple interest on ₹5,000 at 8% per annum for 3 years is:', 'o' => ['₹800', '₹1,000', '₹1,200', '₹1,500']],
                'hi' => ['q' => '₹5,000 पर 8% वार्षिक दर से 3 वर्ष का साधारण ब्याज है:', 'o' => ['₹800', '₹1,000', '₹1,200', '₹1,500']],
                'a'  => 2,
            ],
            [
                's'  => 'math',
                'en' => ['q' => 'One pipe fills a tank in 6 hours and another empties it in 8 hours. If both are opened together, the tank fills in:', 'o' => ['12 hours', '18 hours', '24 hours', '48 hours']],
                'hi' => ['q' => 'एक पाइप एक टंकी को 6 घंटे में भरता है और दूसरा उसे 8 घंटे में खाली करता है। यदि दोनों एक साथ खोले जाएँ, तो टंकी भरेगी:', 'o' => ['12 घंटे में', '18 घंटे में', '24 घंटे में', '48 घंटे में']],
                'a'  => 2,
            ],
            [
                's'  => 'math',
                'en' => ['q' => 'The average of the first 10 natural numbers is:', 'o' => ['5', '5.5', '6', '6.5']],
                'hi' => ['q' => 'प्रथम 10 प्राकृत संख्याओं का औसत है:', 'o' => ['5', '5.5', '6', '6.5']],
                'a'  => 1,
            ],
            [
                's'  => 'math',
                'en' => ['q' => 'A man walks 3 km North and then 4 km East. His straight-line distance from the starting point is:', 'o' => ['5 km', '7 km', '4 km', '6 km']],
                'hi' => ['q' => 'एक व्यक्ति 3 किमी उत्तर की ओर चलता है और फिर 4 किमी पूर्व की ओर। प्रारंभिक बिंदु से उसकी सीधी दूरी है:', 'o' => ['5 किमी', '7 किमी', '4 किमी', '6 किमी']],
                'a'  => 0,
            ],
            [
                's'  => 'math',
                'en' => ['q' => 'What is 12.5% of 640?', 'o' => ['64', '80', '96', '128']],
                'hi' => ['q' => '640 का 12.5% कितना है?', 'o' => ['64', '80', '96', '128']],
                'a'  => 1,
            ],
            [
                's'  => 'math',
                'en' => ['q' => 'If the perimeter of a square is 48 cm, its area is:', 'o' => ['144 cm²', '96 cm²', '124 cm²', '169 cm²']],
                'hi' => ['q' => 'यदि एक वर्ग का परिमाप 48 सेमी है, तो उसका क्षेत्रफल है:', 'o' => ['144 सेमी²', '96 सेमी²', '124 सेमी²', '169 सेमी²']],
                'a'  => 0,
            ],
            [
                's'  => 'math',
                'en' => ['q' => 'Two numbers are in the ratio 4 : 5. If their sum is 135, the larger number is:', 'o' => ['60', '75', '80', '55']],
                'hi' => ['q' => 'दो संख्याएँ 4 : 5 के अनुपात में हैं। यदि उनका योग 135 है, तो बड़ी संख्या है:', 'o' => ['60', '75', '80', '55']],
                'a'  => 1,
            ],
            [
                's'  => 'math',
                'en' => ['q' => 'A can do a piece of work in 12 days and B in 18 days. Working together, they finish it in:', 'o' => ['6 days', '7.2 days', '8 days', '9 days']],
                'hi' => ['q' => 'A एक कार्य को 12 दिन में तथा B उसी कार्य को 18 दिन में कर सकता है। दोनों मिलकर उसे पूरा करेंगे:', 'o' => ['6 दिन में', '7.2 दिन में', '8 दिन में', '9 दिन में']],
                'a'  => 1,
            ],
            [
                's'  => 'math',
                'en' => ['q' => 'The compound interest on ₹1,000 at 10% per annum for 2 years is:', 'o' => ['₹200', '₹210', '₹220', '₹250']],
                'hi' => ['q' => '₹1,000 पर 10% वार्षिक दर से 2 वर्ष का चक्रवृद्धि ब्याज है:', 'o' => ['₹200', '₹210', '₹220', '₹250']],
                'a'  => 1,
            ],
            [
                's'  => 'math',
                'en' => ['q' => 'What is the square root of 1764?', 'o' => ['38', '42', '44', '46']],
                'hi' => ['q' => '1764 का वर्गमूल क्या है?', 'o' => ['38', '42', '44', '46']],
                'a'  => 1,
            ],
            [
                's'  => 'math',
                'en' => ['q' => 'A boat covers 12 km upstream in 3 hours and 12 km downstream in 2 hours. The speed of the stream is:', 'o' => ['1 km/h', '2 km/h', '3 km/h', '4 km/h']],
                'hi' => ['q' => 'एक नाव धारा के प्रतिकूल 12 किमी की दूरी 3 घंटे में तथा धारा के अनुकूल 12 किमी की दूरी 2 घंटे में तय करती है। धारा की चाल है:', 'o' => ['1 किमी/घंटा', '2 किमी/घंटा', '3 किमी/घंटा', '4 किमी/घंटा']],
                'a'  => 0,
            ],
            [
                's'  => 'math',
                'en' => ['q' => 'If cos 60° = 0.5, then sin 30° is equal to:', 'o' => ['0', '0.5', '0.866', '1']],
                'hi' => ['q' => 'यदि cos 60° = 0.5 है, तो sin 30° का मान है:', 'o' => ['0', '0.5', '0.866', '1']],
                'a'  => 1,
            ],
            [
                's'  => 'math',
                'en' => ['q' => 'The volume of a cube of side 5 cm is:', 'o' => ['100 cm³', '125 cm³', '150 cm³', '25 cm³']],
                'hi' => ['q' => '5 सेमी भुजा वाले घन का आयतन है:', 'o' => ['100 सेमी³', '125 सेमी³', '150 सेमी³', '25 सेमी³']],
                'a'  => 1,
            ],
            [
                's'  => 'math',
                'en' => ['q' => 'The diagonal of a rectangle whose sides are 6 cm and 8 cm is:', 'o' => ['10 cm', '12 cm', '14 cm', '9 cm']],
                'hi' => ['q' => 'जिस आयत की भुजाएँ 6 सेमी और 8 सेमी हैं, उसका विकर्ण है:', 'o' => ['10 सेमी', '12 सेमी', '14 सेमी', '9 सेमी']],
                'a'  => 0,
            ],
            [
                's'  => 'math',
                'en' => ['q' => 'If 3x − 7 = 14, then the value of x is:', 'o' => ['5', '6', '7', '8']],
                'hi' => ['q' => 'यदि 3x − 7 = 14 है, तो x का मान है:', 'o' => ['5', '6', '7', '8']],
                'a'  => 2,
            ],
            [
                's'  => 'math',
                'en' => ['q' => 'A discount of 20% on ₹800 gives a selling price of:', 'o' => ['₹600', '₹640', '₹680', '₹720']],
                'hi' => ['q' => '₹800 पर 20% की छूट देने पर विक्रय मूल्य होगा:', 'o' => ['₹600', '₹640', '₹680', '₹720']],
                'a'  => 1,
            ],
            [
                's'  => 'math',
                'en' => ['q' => 'The sum of the interior angles of a triangle is:', 'o' => ['90°', '180°', '270°', '360°']],
                'hi' => ['q' => 'एक त्रिभुज के अंतःकोणों का योग होता है:', 'o' => ['90°', '180°', '270°', '360°']],
                'a'  => 1,
            ],
            [
                's'  => 'math',
                'en' => ['q' => 'What is the median of 3, 7, 2, 9, 5?', 'o' => ['3', '5', '7', '9']],
                'hi' => ['q' => '3, 7, 2, 9, 5 का माध्यिका (median) क्या है?', 'o' => ['3', '5', '7', '9']],
                'a'  => 1,
            ],
            [
                's'  => 'math',
                'en' => ['q' => 'A car travels 240 km in 4 hours. Its speed in metres per second is approximately:', 'o' => ['16.67 m/s', '20 m/s', '60 m/s', '100 m/s']],
                'hi' => ['q' => 'एक कार 4 घंटे में 240 किमी की दूरी तय करती है। मीटर प्रति सेकंड में उसकी चाल लगभग है:', 'o' => ['16.67 मी/से', '20 मी/से', '60 मी/से', '100 मी/से']],
                'a'  => 0,
            ],
            [
                's'  => 'math',
                'en' => ['q' => 'If the circumference of a circle is 44 cm, its radius is (take π = 22/7):', 'o' => ['7 cm', '14 cm', '21 cm', '3.5 cm']],
                'hi' => ['q' => 'यदि एक वृत्त की परिधि 44 सेमी है, तो उसकी त्रिज्या है (π = 22/7 लीजिए):', 'o' => ['7 सेमी', '14 सेमी', '21 सेमी', '3.5 सेमी']],
                'a'  => 0,
            ],
        ];
    }

    /* ═══════════════════════════════════════════════════════════════════════
       General Intelligence & Reasoning — 25 questions
       ═══════════════════════════════════════════════════════════════════════ */
    private static function reasoning(): array
    {
        return [
            [
                's'  => 'reasoning',
                'en' => ['q' => 'Find the next number in the series: 2, 6, 12, 20, 30, ?', 'o' => ['40', '42', '44', '36']],
                'hi' => ['q' => 'श्रृंखला की अगली संख्या ज्ञात कीजिए: 2, 6, 12, 20, 30, ?', 'o' => ['40', '42', '44', '36']],
                'a'  => 1,
            ],
            [
                's'  => 'reasoning',
                'en' => ['q' => 'In a certain code, COMPUTER is written as DPNQVUFS. How is DATA written in that code?', 'o' => ['EBUB', 'DCUB', 'EBUC', 'FBUB']],
                'hi' => ['q' => 'एक निश्चित कूट भाषा में COMPUTER को DPNQVUFS लिखा जाता है। उसी कूट में DATA को कैसे लिखा जाएगा?', 'o' => ['EBUB', 'DCUB', 'EBUC', 'FBUB']],
                'a'  => 0,
            ],
            [
                's'  => 'reasoning',
                'en' => ['q' => 'Statements: All engineers are scientists. Some scientists are teachers. Conclusion: Some engineers are teachers. The conclusion is:', 'o' => ['Definitely true', 'Definitely false', 'Cannot be determined', 'Probably true']],
                'hi' => ['q' => 'कथन: सभी इंजीनियर वैज्ञानिक हैं। कुछ वैज्ञानिक शिक्षक हैं। निष्कर्ष: कुछ इंजीनियर शिक्षक हैं। यह निष्कर्ष है:', 'o' => ['निश्चित रूप से सत्य', 'निश्चित रूप से असत्य', 'निर्धारित नहीं किया जा सकता', 'संभवतः सत्य']],
                'a'  => 2,
            ],
            [
                's'  => 'reasoning',
                'en' => ['q' => 'Find the odd one out: 121, 144, 169, 196, 225, 235', 'o' => ['121', '169', '196', '235']],
                'hi' => ['q' => 'विषम संख्या ज्ञात कीजिए: 121, 144, 169, 196, 225, 235', 'o' => ['121', '169', '196', '235']],
                'a'  => 3,
            ],
            [
                's'  => 'reasoning',
                'en' => ['q' => 'A is the brother of B. C is the daughter of A. D is the sister of B. How is C related to D?', 'o' => ['Niece', 'Daughter', 'Sister', 'Cousin']],
                'hi' => ['q' => 'A, B का भाई है। C, A की पुत्री है। D, B की बहन है। C का D से क्या संबंध है?', 'o' => ['भतीजी', 'पुत्री', 'बहन', 'चचेरी बहन']],
                'a'  => 0,
            ],
            [
                's'  => 'reasoning',
                'en' => ['q' => 'If 2 + 3 = 12, 4 + 5 = 40 and 6 + 7 = 84, then 8 + 9 = ?', 'o' => ['144', '162', '130', '152']],
                'hi' => ['q' => 'यदि 2 + 3 = 12, 4 + 5 = 40 तथा 6 + 7 = 84 है, तो 8 + 9 = ?', 'o' => ['144', '162', '130', '152']],
                'a'  => 0,
            ],
            [
                's'  => 'reasoning',
                'en' => ['q' => 'A triangle initially points upwards and is rotated 90° clockwise at each step. After 3 steps it points:', 'o' => ['Up', 'Right', 'Down', 'Left']],
                'hi' => ['q' => 'एक त्रिभुज प्रारंभ में ऊपर की ओर संकेत करता है तथा प्रत्येक चरण में 90° दक्षिणावर्त घुमाया जाता है। 3 चरणों के बाद वह किस ओर संकेत करेगा?', 'o' => ['ऊपर', 'दाएँ', 'नीचे', 'बाएँ']],
                'a'  => 3,
            ],
            [
                's'  => 'reasoning',
                'en' => ['q' => 'Find the missing number in the series: 3, 9, 27, 81, ?', 'o' => ['162', '243', '189', '216']],
                'hi' => ['q' => 'श्रृंखला में लुप्त संख्या ज्ञात कीजिए: 3, 9, 27, 81, ?', 'o' => ['162', '243', '189', '216']],
                'a'  => 1,
            ],
            [
                's'  => 'reasoning',
                'en' => ['q' => 'In a certain code the value of a word is the sum of the positions of its letters in the English alphabet, so TRAIN = 62 and BUS = 42. What is the value of CAR?', 'o' => ['24', '22', '26', '28']],
                'hi' => ['q' => 'एक निश्चित कूट में किसी शब्द का मान उसके अक्षरों के अंग्रेज़ी वर्णमाला में स्थानों के योग के बराबर होता है, जैसे TRAIN = 62 और BUS = 42। तो CAR का मान क्या होगा?', 'o' => ['24', '22', '26', '28']],
                'a'  => 1,
            ],
            [
                's'  => 'reasoning',
                'en' => ['q' => 'Pointing to a photograph, Ravi said, "He is the son of my father\'s only daughter." How is the person in the photograph related to Ravi?', 'o' => ['Son', 'Nephew', 'Brother', 'Uncle']],
                'hi' => ['q' => 'एक तस्वीर की ओर संकेत करते हुए रवि ने कहा, "यह मेरे पिता की इकलौती पुत्री का पुत्र है।" तस्वीर में दिखाया गया व्यक्ति रवि का क्या लगता है?', 'o' => ['पुत्र', 'भांजा', 'भाई', 'चाचा']],
                'a'  => 1,
            ],
            [
                's'  => 'reasoning',
                'en' => ['q' => 'Which of the following words cannot be formed from the letters of the word EXAMINATION?', 'o' => ['NATION', 'MINE', 'MANGO', 'EXAM']],
                'hi' => ['q' => 'निम्नलिखित में से कौन-सा शब्द EXAMINATION शब्द के अक्षरों से नहीं बनाया जा सकता?', 'o' => ['NATION', 'MINE', 'MANGO', 'EXAM']],
                'a'  => 2,
            ],
            [
                's'  => 'reasoning',
                'en' => ['q' => 'If 1st January falls on a Monday, what day of the week will 31st January be?', 'o' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday']],
                'hi' => ['q' => 'यदि 1 जनवरी को सोमवार है, तो 31 जनवरी को सप्ताह का कौन-सा दिन होगा?', 'o' => ['सोमवार', 'मंगलवार', 'बुधवार', 'गुरुवार']],
                'a'  => 2,
            ],
            [
                's'  => 'reasoning',
                'en' => ['q' => 'How many triangles are there in a rectangle in which both diagonals have been drawn?', 'o' => ['4', '6', '8', '10']],
                'hi' => ['q' => 'एक ऐसे आयत में कितने त्रिभुज होते हैं जिसमें दोनों विकर्ण खींचे गए हों?', 'o' => ['4', '6', '8', '10']],
                'a'  => 2,
            ],
            [
                's'  => 'reasoning',
                'en' => ['q' => 'Complete the analogy: Book : Pages :: Tree : ?', 'o' => ['Branches', 'Roots', 'Leaves', 'Bark']],
                'hi' => ['q' => 'सादृश्य पूरा कीजिए: पुस्तक : पृष्ठ :: वृक्ष : ?', 'o' => ['शाखाएँ', 'जड़ें', 'पत्तियाँ', 'छाल']],
                'a'  => 2,
            ],
            [
                's'  => 'reasoning',
                'en' => ['q' => 'The word AMBULANCE is painted in reverse on the front of an ambulance. In the rear-view mirror of the vehicle ahead, it appears as:', 'o' => ['ECNALUBMA', 'AMBULANCE', 'AMBULANC', 'Cannot be determined']],
                'hi' => ['q' => 'एम्बुलेंस के अगले भाग पर AMBULANCE शब्द उल्टा लिखा जाता है। आगे चल रहे वाहन के रियर-व्यू मिरर में यह किस रूप में दिखाई देता है?', 'o' => ['ECNALUBMA', 'AMBULANCE', 'AMBULANC', 'निर्धारित नहीं किया जा सकता']],
                'a'  => 1,
            ],
            [
                's'  => 'reasoning',
                'en' => ['q' => 'If South-East becomes North, then North-West becomes:', 'o' => ['South', 'East', 'West', 'North-East']],
                'hi' => ['q' => 'यदि दक्षिण-पूर्व, उत्तर बन जाए, तो उत्तर-पश्चिम क्या बन जाएगा?', 'o' => ['दक्षिण', 'पूर्व', 'पश्चिम', 'उत्तर-पूर्व']],
                'a'  => 0,
            ],
            [
                's'  => 'reasoning',
                'en' => ['q' => 'A person walks 5 km South, turns left and walks 3 km, then turns left again and walks 5 km. He is now facing:', 'o' => ['North', 'South', 'East', 'West']],
                'hi' => ['q' => 'एक व्यक्ति 5 किमी दक्षिण की ओर चलता है, बाएँ मुड़कर 3 किमी चलता है, फिर बाएँ मुड़कर 5 किमी चलता है। अब उसका मुख किस दिशा में है?', 'o' => ['उत्तर', 'दक्षिण', 'पूर्व', 'पश्चिम']],
                'a'  => 0,
            ],
            [
                's'  => 'reasoning',
                'en' => ['q' => 'Find the wrong number in the series: 2, 5, 10, 17, 26, 38, 50', 'o' => ['10', '17', '38', '50']],
                'hi' => ['q' => 'श्रृंखला में गलत संख्या ज्ञात कीजिए: 2, 5, 10, 17, 26, 38, 50', 'o' => ['10', '17', '38', '50']],
                'a'  => 2,
            ],
            [
                's'  => 'reasoning',
                'en' => ['q' => 'A clock shows 3:15. What is the angle between the hour hand and the minute hand?', 'o' => ['0°', '7.5°', '15°', '22.5°']],
                'hi' => ['q' => 'एक घड़ी 3:15 का समय दिखा रही है। घंटे की सुई और मिनट की सुई के बीच का कोण क्या है?', 'o' => ['0°', '7.5°', '15°', '22.5°']],
                'a'  => 1,
            ],
            [
                's'  => 'reasoning',
                'en' => ['q' => 'Statements: Some cats are dogs. All dogs are rats. Conclusion: Some cats are rats. The conclusion:', 'o' => ['Follows', 'Does not follow', 'May or may not follow', 'Is contradictory']],
                'hi' => ['q' => 'कथन: कुछ बिल्लियाँ कुत्ते हैं। सभी कुत्ते चूहे हैं। निष्कर्ष: कुछ बिल्लियाँ चूहे हैं। यह निष्कर्ष:', 'o' => ['अनुसरण करता है', 'अनुसरण नहीं करता', 'अनुसरण कर भी सकता है और नहीं भी', 'विरोधाभासी है']],
                'a'  => 0,
            ],
            [
                's'  => 'reasoning',
                'en' => ['q' => 'P is taller than Q, Q is taller than R, and R is shorter than S. Who may be the shortest?', 'o' => ['P', 'Q', 'R', 'S']],
                'hi' => ['q' => 'P, Q से लंबा है; Q, R से लंबा है; तथा R, S से छोटा है। सबसे छोटा कौन हो सकता है?', 'o' => ['P', 'Q', 'R', 'S']],
                'a'  => 2,
            ],
            [
                's'  => 'reasoning',
                'en' => ['q' => 'Arrange the following in a meaningful sequence: (1) Infant (2) Old age (3) Adult (4) Childhood (5) Adolescence', 'o' => ['1, 4, 5, 3, 2', '1, 5, 4, 3, 2', '4, 1, 5, 3, 2', '1, 4, 3, 5, 2']],
                'hi' => ['q' => 'निम्नलिखित को अर्थपूर्ण क्रम में व्यवस्थित कीजिए: (1) शैशव (2) वृद्धावस्था (3) प्रौढ़ावस्था (4) बचपन (5) किशोरावस्था', 'o' => ['1, 4, 5, 3, 2', '1, 5, 4, 3, 2', '4, 1, 5, 3, 2', '1, 4, 3, 5, 2']],
                'a'  => 0,
            ],
            [
                's'  => 'reasoning',
                'en' => ['q' => 'If all A are B and some B are C, then which of the following is definitely correct?', 'o' => ['All A are C', 'Some A are C', 'Some A may be C', 'No A are C']],
                'hi' => ['q' => 'यदि सभी A, B हैं तथा कुछ B, C हैं, तो निम्नलिखित में से कौन-सा निश्चित रूप से सही है?', 'o' => ['सभी A, C हैं', 'कुछ A, C हैं', 'कुछ A, C हो सकते हैं', 'कोई A, C नहीं है']],
                'a'  => 2,
            ],
            [
                's'  => 'reasoning',
                'en' => ['q' => 'How many squares of all sizes are there in a 3 × 3 grid?', 'o' => ['9', '12', '14', '13']],
                'hi' => ['q' => '3 × 3 की ग्रिड में सभी आकारों के कुल कितने वर्ग होते हैं?', 'o' => ['9', '12', '14', '13']],
                'a'  => 2,
            ],
            [
                's'  => 'reasoning',
                'en' => ['q' => 'In a row of 40 students, Rahul is 12th from the left end. What is his position from the right end?', 'o' => ['28th', '29th', '30th', '31st']],
                'hi' => ['q' => '40 विद्यार्थियों की एक पंक्ति में राहुल बाएँ छोर से 12वें स्थान पर है। दाएँ छोर से उसका स्थान क्या है?', 'o' => ['28वाँ', '29वाँ', '30वाँ', '31वाँ']],
                'a'  => 1,
            ],
        ];
    }

    /* ═══════════════════════════════════════════════════════════════════════
       General Science — 25 questions
       ═══════════════════════════════════════════════════════════════════════ */
    private static function science(): array
    {
        return [
            [
                's'  => 'science',
                'en' => ['q' => 'What is the SI unit of electric current?', 'o' => ['Volt', 'Watt', 'Ampere', 'Ohm']],
                'hi' => ['q' => 'विद्युत धारा का SI मात्रक क्या है?', 'o' => ['वोल्ट', 'वाट', 'ऐम्पियर', 'ओम']],
                'a'  => 2,
            ],
            [
                's'  => 'science',
                'en' => ['q' => "Newton's First Law of Motion is also known as the law of:", 'o' => ['Acceleration', 'Inertia', 'Action and Reaction', 'Gravitation']],
                'hi' => ['q' => 'न्यूटन का गति का प्रथम नियम किस नियम के नाम से भी जाना जाता है?', 'o' => ['त्वरण', 'जड़त्व', 'क्रिया-प्रतिक्रिया', 'गुरुत्वाकर्षण']],
                'a'  => 1,
            ],
            [
                's'  => 'science',
                'en' => ['q' => 'Which inert gas is commonly filled in incandescent electric bulbs?', 'o' => ['Oxygen', 'Nitrogen', 'Argon', 'Carbon dioxide']],
                'hi' => ['q' => 'तापदीप्त विद्युत बल्बों में सामान्यतः कौन-सी अक्रिय गैस भरी जाती है?', 'o' => ['ऑक्सीजन', 'नाइट्रोजन', 'आर्गन', 'कार्बन डाइऑक्साइड']],
                'a'  => 2,
            ],
            [
                's'  => 'science',
                'en' => ['q' => 'The chemical formula of common salt is:', 'o' => ['NaCl', 'KCl', 'CaCl₂', 'NaOH']],
                'hi' => ['q' => 'साधारण नमक का रासायनिक सूत्र है:', 'o' => ['NaCl', 'KCl', 'CaCl₂', 'NaOH']],
                'a'  => 0,
            ],
            [
                's'  => 'science',
                'en' => ['q' => 'The pH value of pure water is:', 'o' => ['0', '7', '14', '1']],
                'hi' => ['q' => 'शुद्ध जल का pH मान है:', 'o' => ['0', '7', '14', '1']],
                'a'  => 1,
            ],
            [
                's'  => 'science',
                'en' => ['q' => 'Which metal is in the liquid state at room temperature?', 'o' => ['Iron', 'Mercury', 'Aluminium', 'Lead']],
                'hi' => ['q' => 'कमरे के तापमान पर कौन-सी धातु द्रव अवस्था में होती है?', 'o' => ['लोहा', 'पारा', 'ऐलुमिनियम', 'सीसा']],
                'a'  => 1,
            ],
            [
                's'  => 'science',
                'en' => ['q' => 'Which gas is released during photosynthesis?', 'o' => ['Carbon dioxide', 'Oxygen', 'Nitrogen', 'Hydrogen']],
                'hi' => ['q' => 'प्रकाश संश्लेषण के दौरान कौन-सी गैस मुक्त होती है?', 'o' => ['कार्बन डाइऑक्साइड', 'ऑक्सीजन', 'नाइट्रोजन', 'हाइड्रोजन']],
                'a'  => 1,
            ],
            [
                's'  => 'science',
                'en' => ['q' => 'Which organelle is called the powerhouse of the cell?', 'o' => ['Nucleus', 'Ribosome', 'Mitochondria', 'Golgi body']],
                'hi' => ['q' => 'किस कोशिकांग को कोशिका का ऊर्जा गृह (पावरहाउस) कहा जाता है?', 'o' => ['केंद्रक', 'राइबोसोम', 'माइटोकॉन्ड्रिया', 'गॉल्जीकाय']],
                'a'  => 2,
            ],
            [
                's'  => 'science',
                'en' => ['q' => 'Which vitamin is produced in human skin on exposure to sunlight?', 'o' => ['Vitamin A', 'Vitamin B12', 'Vitamin C', 'Vitamin D']],
                'hi' => ['q' => 'सूर्य के प्रकाश के संपर्क में आने पर मानव त्वचा में कौन-सा विटामिन बनता है?', 'o' => ['विटामिन A', 'विटामिन B12', 'विटामिन C', 'विटामिन D']],
                'a'  => 3,
            ],
            [
                's'  => 'science',
                'en' => ['q' => 'Sound cannot travel through:', 'o' => ['Water', 'Air', 'Vacuum', 'Steel']],
                'hi' => ['q' => 'ध्वनि किसमें से होकर नहीं चल सकती?', 'o' => ['जल', 'वायु', 'निर्वात', 'इस्पात']],
                'a'  => 2,
            ],
            [
                's'  => 'science',
                'en' => ['q' => 'The SI unit of force is:', 'o' => ['Joule', 'Newton', 'Pascal', 'Watt']],
                'hi' => ['q' => 'बल का SI मात्रक है:', 'o' => ['जूल', 'न्यूटन', 'पास्कल', 'वाट']],
                'a'  => 1,
            ],
            [
                's'  => 'science',
                'en' => ['q' => 'Which element has the atomic number 1?', 'o' => ['Helium', 'Oxygen', 'Hydrogen', 'Carbon']],
                'hi' => ['q' => 'किस तत्व की परमाणु संख्या 1 है?', 'o' => ['हीलियम', 'ऑक्सीजन', 'हाइड्रोजन', 'कार्बन']],
                'a'  => 2,
            ],
            [
                's'  => 'science',
                'en' => ['q' => 'Brass is an alloy of:', 'o' => ['Copper and Zinc', 'Copper and Tin', 'Iron and Carbon', 'Lead and Tin']],
                'hi' => ['q' => 'पीतल किसकी मिश्रधातु है?', 'o' => ['ताँबा और जस्ता', 'ताँबा और टिन', 'लोहा और कार्बन', 'सीसा और टिन']],
                'a'  => 0,
            ],
            [
                's'  => 'science',
                'en' => ['q' => 'Which organ of the human body produces insulin?', 'o' => ['Liver', 'Kidney', 'Pancreas', 'Heart']],
                'hi' => ['q' => 'मानव शरीर का कौन-सा अंग इंसुलिन का उत्पादन करता है?', 'o' => ['यकृत', 'वृक्क', 'अग्न्याशय', 'हृदय']],
                'a'  => 2,
            ],
            [
                's'  => 'science',
                'en' => ['q' => 'The speed of sound in air at room temperature is approximately:', 'o' => ['343 m/s', '300 m/s', '500 m/s', '100 m/s']],
                'hi' => ['q' => 'कमरे के तापमान पर वायु में ध्वनि की चाल लगभग कितनी होती है?', 'o' => ['343 मी/से', '300 मी/से', '500 मी/से', '100 मी/से']],
                'a'  => 0,
            ],
            [
                's'  => 'science',
                'en' => ['q' => 'Rusting of iron is an example of:', 'o' => ['Physical change', 'Chemical change', 'Nuclear reaction', 'No change']],
                'hi' => ['q' => 'लोहे में जंग लगना किसका उदाहरण है?', 'o' => ['भौतिक परिवर्तन', 'रासायनिक परिवर्तन', 'नाभिकीय अभिक्रिया', 'कोई परिवर्तन नहीं']],
                'a'  => 1,
            ],
            [
                's'  => 'science',
                'en' => ['q' => 'The number of chromosomes in a normal human body cell is:', 'o' => ['23', '44', '46', '48']],
                'hi' => ['q' => 'सामान्य मानव शरीर कोशिका में गुणसूत्रों की संख्या होती है:', 'o' => ['23', '44', '46', '48']],
                'a'  => 2,
            ],
            [
                's'  => 'science',
                'en' => ['q' => 'Which type of lens is used to correct myopia (short-sightedness)?', 'o' => ['Convex lens', 'Concave lens', 'Bifocal lens', 'Cylindrical lens']],
                'hi' => ['q' => 'निकट दृष्टि दोष (मायोपिया) को ठीक करने के लिए किस प्रकार के लेंस का प्रयोग किया जाता है?', 'o' => ['उत्तल लेंस', 'अवतल लेंस', 'द्विफोकसी लेंस', 'बेलनाकार लेंस']],
                'a'  => 1,
            ],
            [
                's'  => 'science',
                'en' => ['q' => 'Which gas is chiefly responsible for the greenhouse effect?', 'o' => ['Oxygen', 'Nitrogen', 'Carbon dioxide', 'Hydrogen']],
                'hi' => ['q' => 'ग्रीनहाउस प्रभाव के लिए मुख्य रूप से कौन-सी गैस उत्तरदायी है?', 'o' => ['ऑक्सीजन', 'नाइट्रोजन', 'कार्बन डाइऑक्साइड', 'हाइड्रोजन']],
                'a'  => 2,
            ],
            [
                's'  => 'science',
                'en' => ['q' => 'An electric fuse works on the principle of the:', 'o' => ['Chemical effect of current', 'Magnetic effect of current', 'Heating effect of current', 'Electromagnetic induction']],
                'hi' => ['q' => 'विद्युत फ्यूज किस सिद्धांत पर कार्य करता है?', 'o' => ['धारा का रासायनिक प्रभाव', 'धारा का चुंबकीय प्रभाव', 'धारा का ऊष्मीय प्रभाव', 'विद्युतचुंबकीय प्रेरण']],
                'a'  => 2,
            ],
            [
                's'  => 'science',
                'en' => ['q' => 'Which blood group is known as the universal donor?', 'o' => ['A', 'B', 'AB', 'O']],
                'hi' => ['q' => 'किस रक्त समूह को सर्वदाता (यूनिवर्सल डोनर) कहा जाता है?', 'o' => ['A', 'B', 'AB', 'O']],
                'a'  => 3,
            ],
            [
                's'  => 'science',
                'en' => ['q' => "According to Ohm's law, V is equal to:", 'o' => ['I × R', 'I / R', 'I + R', 'I − R']],
                'hi' => ['q' => 'ओम के नियम के अनुसार, V किसके बराबर होता है?', 'o' => ['I × R', 'I / R', 'I + R', 'I − R']],
                'a'  => 0,
            ],
            [
                's'  => 'science',
                'en' => ['q' => 'Which cell organelle is responsible for protein synthesis?', 'o' => ['Mitochondria', 'Ribosome', 'Lysosome', 'Nucleus']],
                'hi' => ['q' => 'प्रोटीन संश्लेषण के लिए कौन-सा कोशिकांग उत्तरदायी है?', 'o' => ['माइटोकॉन्ड्रिया', 'राइबोसोम', 'लाइसोसोम', 'केंद्रक']],
                'a'  => 1,
            ],
            [
                's'  => 'science',
                'en' => ['q' => 'The direct conversion of a solid into a gas is called:', 'o' => ['Evaporation', 'Condensation', 'Sublimation', 'Deposition']],
                'hi' => ['q' => 'ठोस का सीधे गैस में परिवर्तित होना क्या कहलाता है?', 'o' => ['वाष्पीकरण', 'संघनन', 'ऊर्ध्वपातन', 'निक्षेपण']],
                'a'  => 2,
            ],
            [
                's'  => 'science',
                'en' => ['q' => 'Which acid is present in the human stomach?', 'o' => ['Sulphuric acid', 'Nitric acid', 'Hydrochloric acid', 'Acetic acid']],
                'hi' => ['q' => 'मानव आमाशय में कौन-सा अम्ल पाया जाता है?', 'o' => ['सल्फ्यूरिक अम्ल', 'नाइट्रिक अम्ल', 'हाइड्रोक्लोरिक अम्ल', 'एसिटिक अम्ल']],
                'a'  => 2,
            ],
        ];
    }
}
