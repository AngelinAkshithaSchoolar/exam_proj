{{--
════════════════════════════════════════════════════════════════════════════════
  RRB ALP 2026 — CBT 1 (First Stage)  ·  complete mock test in one Blade file
════════════════════════════════════════════════════════════════════════════════

  Drop this at:  resources/views/alp-cbt1.blade.php
  Route (one line in routes/web.php):

      Route::view('/alp-cbt1', 'alp-cbt1')->name('alp-cbt1');

  Then open  http://127.0.0.1:8000/alp-cbt1

  ── What is inside ──────────────────────────────────────────────────────────
  Five screens live in this one document and are swapped by JavaScript, so
  there is no server round trip once the test starts and nothing to wire up:

      1  General instructions      (how the CBT software works)
      2  Instructions + language + declaration
      3  The test window           (palette, timer, save & next, mark for review)
      4  Result                    (score, section split, qualifying status)
      5  Answer review             (filterable, question by question)

  ── Exam pattern (2026 revised) ─────────────────────────────────────────────
      75 questions · 75 marks · 60 minutes · −1/3 mark per wrong answer
      Sections: Mathematics, General Intelligence & Reasoning, General Science
      General Awareness has been dropped from CBT 1.
      CBT 1 is a screening test — its marks are NOT part of the final merit.

  ── ONE THING TO KNOW BEFORE YOU SHIP THIS ──────────────────────────────────
  Because everything is in one file, the answer key ('a' on each question)
  reaches the browser. A student who opens View Source can read every answer.
  That is fine for practice; it is not fine for anything ranked.

  When you need it locked down, the change is small and marked in the code:
  search for  ANSWER-KEY BOUNDARY  below. Send $PLAYER to the page, keep $KEY
  on the server, and POST the answers to a route that scores them.
════════════════════════════════════════════════════════════════════════════════
--}}

@php
/*
|--------------------------------------------------------------------------
| Question bank
|--------------------------------------------------------------------------
| One row per question:
|
|     's' => section key            'math' | 'reasoning' | 'science'
|     'a' => correct option index   0..3
|     'en'/'hi' => ['q' => question text, 'o' => [four options]]
|
| Add, remove or reorder freely — the section tabs, the palette and the
| result page all count from this array. Nothing below hard-codes 25 or 75.
*/
$Q = [
    /* ── Mathematics ── */
    ['s'=>'math', 'a'=>2,
     'en'=>['q'=>'What is the HCF of 24 and 36?',
            'o'=>['6', '8', '12', '18']],
     'hi'=>['q'=>'24 और 36 का महत्तम समापवर्तक (HCF) क्या है?',
            'o'=>['6', '8', '12', '18']]],
    ['s'=>'math', 'a'=>1,
     'en'=>['q'=>'The LCM of 12, 15 and 20 is:',
            'o'=>['30', '60', '120', '180']],
     'hi'=>['q'=>'12, 15 और 20 का लघुत्तम समापवर्त्य (LCM) है:',
            'o'=>['30', '60', '120', '180']]],
    ['s'=>'math', 'a'=>0,
     'en'=>['q'=>'A train 150 m long crosses a pole in 15 seconds. Its speed is:',
            'o'=>['36 km/h', '10 km/h', '25 km/h', '40 km/h']],
     'hi'=>['q'=>'150 मीटर लंबी एक रेलगाड़ी एक खंभे को 15 सेकंड में पार करती है। इसकी चाल है:',
            'o'=>['36 किमी/घंटा', '10 किमी/घंटा', '25 किमी/घंटा', '40 किमी/घंटा']]],
    ['s'=>'math', 'a'=>2,
     'en'=>['q'=>'The ratio of the ages of A and B is 3 : 5 and the sum of their ages is 48 years. Find the age of B.',
            'o'=>['18 years', '28 years', '30 years', '32 years']],
     'hi'=>['q'=>'A और B की आयु का अनुपात 3 : 5 है तथा उनकी आयु का योग 48 वर्ष है। B की आयु ज्ञात कीजिए।',
            'o'=>['18 वर्ष', '28 वर्ष', '30 वर्ष', '32 वर्ष']]],
    ['s'=>'math', 'a'=>1,
     'en'=>['q'=>'A shopkeeper buys an item for ₹400 and sells it for ₹500. His profit percentage is:',
            'o'=>['20%', '25%', '30%', '15%']],
     'hi'=>['q'=>'एक दुकानदार एक वस्तु ₹400 में खरीदकर ₹500 में बेचता है। उसका लाभ प्रतिशत है:',
            'o'=>['20%', '25%', '30%', '15%']]],
    ['s'=>'math', 'a'=>2,
     'en'=>['q'=>'The simple interest on ₹5,000 at 8% per annum for 3 years is:',
            'o'=>['₹800', '₹1,000', '₹1,200', '₹1,500']],
     'hi'=>['q'=>'₹5,000 पर 8% वार्षिक दर से 3 वर्ष का साधारण ब्याज है:',
            'o'=>['₹800', '₹1,000', '₹1,200', '₹1,500']]],
    ['s'=>'math', 'a'=>2,
     'en'=>['q'=>'One pipe fills a tank in 6 hours and another empties it in 8 hours. If both are opened together, the tank fills in:',
            'o'=>['12 hours', '18 hours', '24 hours', '48 hours']],
     'hi'=>['q'=>'एक पाइप एक टंकी को 6 घंटे में भरता है और दूसरा उसे 8 घंटे में खाली करता है। यदि दोनों एक साथ खोले जाएँ, तो टंकी भरेगी:',
            'o'=>['12 घंटे में', '18 घंटे में', '24 घंटे में', '48 घंटे में']]],
    ['s'=>'math', 'a'=>1,
     'en'=>['q'=>'The average of the first 10 natural numbers is:',
            'o'=>['5', '5.5', '6', '6.5']],
     'hi'=>['q'=>'प्रथम 10 प्राकृत संख्याओं का औसत है:',
            'o'=>['5', '5.5', '6', '6.5']]],
    ['s'=>'math', 'a'=>0,
     'en'=>['q'=>'A man walks 3 km North and then 4 km East. His straight-line distance from the starting point is:',
            'o'=>['5 km', '7 km', '4 km', '6 km']],
     'hi'=>['q'=>'एक व्यक्ति 3 किमी उत्तर की ओर चलता है और फिर 4 किमी पूर्व की ओर। प्रारंभिक बिंदु से उसकी सीधी दूरी है:',
            'o'=>['5 किमी', '7 किमी', '4 किमी', '6 किमी']]],
    ['s'=>'math', 'a'=>1,
     'en'=>['q'=>'What is 12.5% of 640?',
            'o'=>['64', '80', '96', '128']],
     'hi'=>['q'=>'640 का 12.5% कितना है?',
            'o'=>['64', '80', '96', '128']]],
    ['s'=>'math', 'a'=>0,
     'en'=>['q'=>'If the perimeter of a square is 48 cm, its area is:',
            'o'=>['144 cm²', '96 cm²', '124 cm²', '169 cm²']],
     'hi'=>['q'=>'यदि एक वर्ग का परिमाप 48 सेमी है, तो उसका क्षेत्रफल है:',
            'o'=>['144 सेमी²', '96 सेमी²', '124 सेमी²', '169 सेमी²']]],
    ['s'=>'math', 'a'=>1,
     'en'=>['q'=>'Two numbers are in the ratio 4 : 5. If their sum is 135, the larger number is:',
            'o'=>['60', '75', '80', '55']],
     'hi'=>['q'=>'दो संख्याएँ 4 : 5 के अनुपात में हैं। यदि उनका योग 135 है, तो बड़ी संख्या है:',
            'o'=>['60', '75', '80', '55']]],
    ['s'=>'math', 'a'=>1,
     'en'=>['q'=>'A can do a piece of work in 12 days and B in 18 days. Working together, they finish it in:',
            'o'=>['6 days', '7.2 days', '8 days', '9 days']],
     'hi'=>['q'=>'A एक कार्य को 12 दिन में तथा B उसी कार्य को 18 दिन में कर सकता है। दोनों मिलकर उसे पूरा करेंगे:',
            'o'=>['6 दिन में', '7.2 दिन में', '8 दिन में', '9 दिन में']]],
    ['s'=>'math', 'a'=>1,
     'en'=>['q'=>'The compound interest on ₹1,000 at 10% per annum for 2 years is:',
            'o'=>['₹200', '₹210', '₹220', '₹250']],
     'hi'=>['q'=>'₹1,000 पर 10% वार्षिक दर से 2 वर्ष का चक्रवृद्धि ब्याज है:',
            'o'=>['₹200', '₹210', '₹220', '₹250']]],
    ['s'=>'math', 'a'=>1,
     'en'=>['q'=>'What is the square root of 1764?',
            'o'=>['38', '42', '44', '46']],
     'hi'=>['q'=>'1764 का वर्गमूल क्या है?',
            'o'=>['38', '42', '44', '46']]],
    ['s'=>'math', 'a'=>0,
     'en'=>['q'=>'A boat covers 12 km upstream in 3 hours and 12 km downstream in 2 hours. The speed of the stream is:',
            'o'=>['1 km/h', '2 km/h', '3 km/h', '4 km/h']],
     'hi'=>['q'=>'एक नाव धारा के प्रतिकूल 12 किमी की दूरी 3 घंटे में तथा धारा के अनुकूल 12 किमी की दूरी 2 घंटे में तय करती है। धारा की चाल है:',
            'o'=>['1 किमी/घंटा', '2 किमी/घंटा', '3 किमी/घंटा', '4 किमी/घंटा']]],
    ['s'=>'math', 'a'=>1,
     'en'=>['q'=>'If cos 60° = 0.5, then sin 30° is equal to:',
            'o'=>['0', '0.5', '0.866', '1']],
     'hi'=>['q'=>'यदि cos 60° = 0.5 है, तो sin 30° का मान है:',
            'o'=>['0', '0.5', '0.866', '1']]],
    ['s'=>'math', 'a'=>1,
     'en'=>['q'=>'The volume of a cube of side 5 cm is:',
            'o'=>['100 cm³', '125 cm³', '150 cm³', '25 cm³']],
     'hi'=>['q'=>'5 सेमी भुजा वाले घन का आयतन है:',
            'o'=>['100 सेमी³', '125 सेमी³', '150 सेमी³', '25 सेमी³']]],
    ['s'=>'math', 'a'=>0,
     'en'=>['q'=>'The diagonal of a rectangle whose sides are 6 cm and 8 cm is:',
            'o'=>['10 cm', '12 cm', '14 cm', '9 cm']],
     'hi'=>['q'=>'जिस आयत की भुजाएँ 6 सेमी और 8 सेमी हैं, उसका विकर्ण है:',
            'o'=>['10 सेमी', '12 सेमी', '14 सेमी', '9 सेमी']]],
    ['s'=>'math', 'a'=>2,
     'en'=>['q'=>'If 3x − 7 = 14, then the value of x is:',
            'o'=>['5', '6', '7', '8']],
     'hi'=>['q'=>'यदि 3x − 7 = 14 है, तो x का मान है:',
            'o'=>['5', '6', '7', '8']]],
    ['s'=>'math', 'a'=>1,
     'en'=>['q'=>'A discount of 20% on ₹800 gives a selling price of:',
            'o'=>['₹600', '₹640', '₹680', '₹720']],
     'hi'=>['q'=>'₹800 पर 20% की छूट देने पर विक्रय मूल्य होगा:',
            'o'=>['₹600', '₹640', '₹680', '₹720']]],
    ['s'=>'math', 'a'=>1,
     'en'=>['q'=>'The sum of the interior angles of a triangle is:',
            'o'=>['90°', '180°', '270°', '360°']],
     'hi'=>['q'=>'एक त्रिभुज के अंतःकोणों का योग होता है:',
            'o'=>['90°', '180°', '270°', '360°']]],
    ['s'=>'math', 'a'=>1,
     'en'=>['q'=>'What is the median of 3, 7, 2, 9, 5?',
            'o'=>['3', '5', '7', '9']],
     'hi'=>['q'=>'3, 7, 2, 9, 5 का माध्यिका (median) क्या है?',
            'o'=>['3', '5', '7', '9']]],
    ['s'=>'math', 'a'=>0,
     'en'=>['q'=>'A car travels 240 km in 4 hours. Its speed in metres per second is approximately:',
            'o'=>['16.67 m/s', '20 m/s', '60 m/s', '100 m/s']],
     'hi'=>['q'=>'एक कार 4 घंटे में 240 किमी की दूरी तय करती है। मीटर प्रति सेकंड में उसकी चाल लगभग है:',
            'o'=>['16.67 मी/से', '20 मी/से', '60 मी/से', '100 मी/से']]],
    ['s'=>'math', 'a'=>0,
     'en'=>['q'=>'If the circumference of a circle is 44 cm, its radius is (take π = 22/7):',
            'o'=>['7 cm', '14 cm', '21 cm', '3.5 cm']],
     'hi'=>['q'=>'यदि एक वृत्त की परिधि 44 सेमी है, तो उसकी त्रिज्या है (π = 22/7 लीजिए):',
            'o'=>['7 सेमी', '14 सेमी', '21 सेमी', '3.5 सेमी']]],

    /* ── General Intelligence & Reasoning ── */
    ['s'=>'reasoning', 'a'=>1,
     'en'=>['q'=>'Find the next number in the series: 2, 6, 12, 20, 30, ?',
            'o'=>['40', '42', '44', '36']],
     'hi'=>['q'=>'श्रृंखला की अगली संख्या ज्ञात कीजिए: 2, 6, 12, 20, 30, ?',
            'o'=>['40', '42', '44', '36']]],
    ['s'=>'reasoning', 'a'=>0,
     'en'=>['q'=>'In a certain code, COMPUTER is written as DPNQVUFS. How is DATA written in that code?',
            'o'=>['EBUB', 'DCUB', 'EBUC', 'FBUB']],
     'hi'=>['q'=>'एक निश्चित कूट भाषा में COMPUTER को DPNQVUFS लिखा जाता है। उसी कूट में DATA को कैसे लिखा जाएगा?',
            'o'=>['EBUB', 'DCUB', 'EBUC', 'FBUB']]],
    ['s'=>'reasoning', 'a'=>2,
     'en'=>['q'=>'Statements: All engineers are scientists. Some scientists are teachers. Conclusion: Some engineers are teachers. The conclusion is:',
            'o'=>['Definitely true', 'Definitely false', 'Cannot be determined', 'Probably true']],
     'hi'=>['q'=>'कथन: सभी इंजीनियर वैज्ञानिक हैं। कुछ वैज्ञानिक शिक्षक हैं। निष्कर्ष: कुछ इंजीनियर शिक्षक हैं। यह निष्कर्ष है:',
            'o'=>['निश्चित रूप से सत्य', 'निश्चित रूप से असत्य', 'निर्धारित नहीं किया जा सकता', 'संभवतः सत्य']]],
    ['s'=>'reasoning', 'a'=>3,
     'en'=>['q'=>'Find the odd one out: 121, 144, 169, 196, 225, 235',
            'o'=>['121', '169', '196', '235']],
     'hi'=>['q'=>'विषम संख्या ज्ञात कीजिए: 121, 144, 169, 196, 225, 235',
            'o'=>['121', '169', '196', '235']]],
    ['s'=>'reasoning', 'a'=>0,
     'en'=>['q'=>'A is the brother of B. C is the daughter of A. D is the sister of B. How is C related to D?',
            'o'=>['Niece', 'Daughter', 'Sister', 'Cousin']],
     'hi'=>['q'=>'A, B का भाई है। C, A की पुत्री है। D, B की बहन है। C का D से क्या संबंध है?',
            'o'=>['भतीजी', 'पुत्री', 'बहन', 'चचेरी बहन']]],
    ['s'=>'reasoning', 'a'=>0,
     'en'=>['q'=>'If 2 + 3 = 12, 4 + 5 = 40 and 6 + 7 = 84, then 8 + 9 = ?',
            'o'=>['144', '162', '130', '152']],
     'hi'=>['q'=>'यदि 2 + 3 = 12, 4 + 5 = 40 तथा 6 + 7 = 84 है, तो 8 + 9 = ?',
            'o'=>['144', '162', '130', '152']]],
    ['s'=>'reasoning', 'a'=>3,
     'en'=>['q'=>'A triangle initially points upwards and is rotated 90° clockwise at each step. After 3 steps it points:',
            'o'=>['Up', 'Right', 'Down', 'Left']],
     'hi'=>['q'=>'एक त्रिभुज प्रारंभ में ऊपर की ओर संकेत करता है तथा प्रत्येक चरण में 90° दक्षिणावर्त घुमाया जाता है। 3 चरणों के बाद वह किस ओर संकेत करेगा?',
            'o'=>['ऊपर', 'दाएँ', 'नीचे', 'बाएँ']]],
    ['s'=>'reasoning', 'a'=>1,
     'en'=>['q'=>'Find the missing number in the series: 3, 9, 27, 81, ?',
            'o'=>['162', '243', '189', '216']],
     'hi'=>['q'=>'श्रृंखला में लुप्त संख्या ज्ञात कीजिए: 3, 9, 27, 81, ?',
            'o'=>['162', '243', '189', '216']]],
    ['s'=>'reasoning', 'a'=>1,
     'en'=>['q'=>'In a certain code the value of a word is the sum of the positions of its letters in the English alphabet, so TRAIN = 62 and BUS = 42. What is the value of CAR?',
            'o'=>['24', '22', '26', '28']],
     'hi'=>['q'=>'एक निश्चित कूट में किसी शब्द का मान उसके अक्षरों के अंग्रेज़ी वर्णमाला में स्थानों के योग के बराबर होता है, जैसे TRAIN = 62 और BUS = 42। तो CAR का मान क्या होगा?',
            'o'=>['24', '22', '26', '28']]],
    ['s'=>'reasoning', 'a'=>1,
     'en'=>['q'=>'Pointing to a photograph, Ravi said, "He is the son of my father\'s only daughter." How is the person in the photograph related to Ravi?',
            'o'=>['Son', 'Nephew', 'Brother', 'Uncle']],
     'hi'=>['q'=>'एक तस्वीर की ओर संकेत करते हुए रवि ने कहा, "यह मेरे पिता की इकलौती पुत्री का पुत्र है।" तस्वीर में दिखाया गया व्यक्ति रवि का क्या लगता है?',
            'o'=>['पुत्र', 'भांजा', 'भाई', 'चाचा']]],
    ['s'=>'reasoning', 'a'=>2,
     'en'=>['q'=>'Which of the following words cannot be formed from the letters of the word EXAMINATION?',
            'o'=>['NATION', 'MINE', 'MANGO', 'EXAM']],
     'hi'=>['q'=>'निम्नलिखित में से कौन-सा शब्द EXAMINATION शब्द के अक्षरों से नहीं बनाया जा सकता?',
            'o'=>['NATION', 'MINE', 'MANGO', 'EXAM']]],
    ['s'=>'reasoning', 'a'=>2,
     'en'=>['q'=>'If 1st January falls on a Monday, what day of the week will 31st January be?',
            'o'=>['Monday', 'Tuesday', 'Wednesday', 'Thursday']],
     'hi'=>['q'=>'यदि 1 जनवरी को सोमवार है, तो 31 जनवरी को सप्ताह का कौन-सा दिन होगा?',
            'o'=>['सोमवार', 'मंगलवार', 'बुधवार', 'गुरुवार']]],
    ['s'=>'reasoning', 'a'=>2,
     'en'=>['q'=>'How many triangles are there in a rectangle in which both diagonals have been drawn?',
            'o'=>['4', '6', '8', '10']],
     'hi'=>['q'=>'एक ऐसे आयत में कितने त्रिभुज होते हैं जिसमें दोनों विकर्ण खींचे गए हों?',
            'o'=>['4', '6', '8', '10']]],
    ['s'=>'reasoning', 'a'=>2,
     'en'=>['q'=>'Complete the analogy: Book : Pages :: Tree : ?',
            'o'=>['Branches', 'Roots', 'Leaves', 'Bark']],
     'hi'=>['q'=>'सादृश्य पूरा कीजिए: पुस्तक : पृष्ठ :: वृक्ष : ?',
            'o'=>['शाखाएँ', 'जड़ें', 'पत्तियाँ', 'छाल']]],
    ['s'=>'reasoning', 'a'=>1,
     'en'=>['q'=>'The word AMBULANCE is painted in reverse on the front of an ambulance. In the rear-view mirror of the vehicle ahead, it appears as:',
            'o'=>['ECNALUBMA', 'AMBULANCE', 'AMBULANC', 'Cannot be determined']],
     'hi'=>['q'=>'एम्बुलेंस के अगले भाग पर AMBULANCE शब्द उल्टा लिखा जाता है। आगे चल रहे वाहन के रियर-व्यू मिरर में यह किस रूप में दिखाई देता है?',
            'o'=>['ECNALUBMA', 'AMBULANCE', 'AMBULANC', 'निर्धारित नहीं किया जा सकता']]],
    ['s'=>'reasoning', 'a'=>0,
     'en'=>['q'=>'If South-East becomes North, then North-West becomes:',
            'o'=>['South', 'East', 'West', 'North-East']],
     'hi'=>['q'=>'यदि दक्षिण-पूर्व, उत्तर बन जाए, तो उत्तर-पश्चिम क्या बन जाएगा?',
            'o'=>['दक्षिण', 'पूर्व', 'पश्चिम', 'उत्तर-पूर्व']]],
    ['s'=>'reasoning', 'a'=>0,
     'en'=>['q'=>'A person walks 5 km South, turns left and walks 3 km, then turns left again and walks 5 km. He is now facing:',
            'o'=>['North', 'South', 'East', 'West']],
     'hi'=>['q'=>'एक व्यक्ति 5 किमी दक्षिण की ओर चलता है, बाएँ मुड़कर 3 किमी चलता है, फिर बाएँ मुड़कर 5 किमी चलता है। अब उसका मुख किस दिशा में है?',
            'o'=>['उत्तर', 'दक्षिण', 'पूर्व', 'पश्चिम']]],
    ['s'=>'reasoning', 'a'=>2,
     'en'=>['q'=>'Find the wrong number in the series: 2, 5, 10, 17, 26, 38, 50',
            'o'=>['10', '17', '38', '50']],
     'hi'=>['q'=>'श्रृंखला में गलत संख्या ज्ञात कीजिए: 2, 5, 10, 17, 26, 38, 50',
            'o'=>['10', '17', '38', '50']]],
    ['s'=>'reasoning', 'a'=>1,
     'en'=>['q'=>'A clock shows 3:15. What is the angle between the hour hand and the minute hand?',
            'o'=>['0°', '7.5°', '15°', '22.5°']],
     'hi'=>['q'=>'एक घड़ी 3:15 का समय दिखा रही है। घंटे की सुई और मिनट की सुई के बीच का कोण क्या है?',
            'o'=>['0°', '7.5°', '15°', '22.5°']]],
    ['s'=>'reasoning', 'a'=>0,
     'en'=>['q'=>'Statements: Some cats are dogs. All dogs are rats. Conclusion: Some cats are rats. The conclusion:',
            'o'=>['Follows', 'Does not follow', 'May or may not follow', 'Is contradictory']],
     'hi'=>['q'=>'कथन: कुछ बिल्लियाँ कुत्ते हैं। सभी कुत्ते चूहे हैं। निष्कर्ष: कुछ बिल्लियाँ चूहे हैं। यह निष्कर्ष:',
            'o'=>['अनुसरण करता है', 'अनुसरण नहीं करता', 'अनुसरण कर भी सकता है और नहीं भी', 'विरोधाभासी है']]],
    ['s'=>'reasoning', 'a'=>2,
     'en'=>['q'=>'P is taller than Q, Q is taller than R, and R is shorter than S. Who may be the shortest?',
            'o'=>['P', 'Q', 'R', 'S']],
     'hi'=>['q'=>'P, Q से लंबा है; Q, R से लंबा है; तथा R, S से छोटा है। सबसे छोटा कौन हो सकता है?',
            'o'=>['P', 'Q', 'R', 'S']]],
    ['s'=>'reasoning', 'a'=>0,
     'en'=>['q'=>'Arrange the following in a meaningful sequence: (1) Infant (2) Old age (3) Adult (4) Childhood (5) Adolescence',
            'o'=>['1, 4, 5, 3, 2', '1, 5, 4, 3, 2', '4, 1, 5, 3, 2', '1, 4, 3, 5, 2']],
     'hi'=>['q'=>'निम्नलिखित को अर्थपूर्ण क्रम में व्यवस्थित कीजिए: (1) शैशव (2) वृद्धावस्था (3) प्रौढ़ावस्था (4) बचपन (5) किशोरावस्था',
            'o'=>['1, 4, 5, 3, 2', '1, 5, 4, 3, 2', '4, 1, 5, 3, 2', '1, 4, 3, 5, 2']]],
    ['s'=>'reasoning', 'a'=>2,
     'en'=>['q'=>'If all A are B and some B are C, then which of the following is definitely correct?',
            'o'=>['All A are C', 'Some A are C', 'Some A may be C', 'No A are C']],
     'hi'=>['q'=>'यदि सभी A, B हैं तथा कुछ B, C हैं, तो निम्नलिखित में से कौन-सा निश्चित रूप से सही है?',
            'o'=>['सभी A, C हैं', 'कुछ A, C हैं', 'कुछ A, C हो सकते हैं', 'कोई A, C नहीं है']]],
    ['s'=>'reasoning', 'a'=>2,
     'en'=>['q'=>'How many squares of all sizes are there in a 3 × 3 grid?',
            'o'=>['9', '12', '14', '13']],
     'hi'=>['q'=>'3 × 3 की ग्रिड में सभी आकारों के कुल कितने वर्ग होते हैं?',
            'o'=>['9', '12', '14', '13']]],
    ['s'=>'reasoning', 'a'=>1,
     'en'=>['q'=>'In a row of 40 students, Rahul is 12th from the left end. What is his position from the right end?',
            'o'=>['28th', '29th', '30th', '31st']],
     'hi'=>['q'=>'40 विद्यार्थियों की एक पंक्ति में राहुल बाएँ छोर से 12वें स्थान पर है। दाएँ छोर से उसका स्थान क्या है?',
            'o'=>['28वाँ', '29वाँ', '30वाँ', '31वाँ']]],

    /* ── General Science ── */
    ['s'=>'science', 'a'=>2,
     'en'=>['q'=>'What is the SI unit of electric current?',
            'o'=>['Volt', 'Watt', 'Ampere', 'Ohm']],
     'hi'=>['q'=>'विद्युत धारा का SI मात्रक क्या है?',
            'o'=>['वोल्ट', 'वाट', 'ऐम्पियर', 'ओम']]],
    ['s'=>'science', 'a'=>1,
     'en'=>['q'=>'Newton\'s First Law of Motion is also known as the law of:',
            'o'=>['Acceleration', 'Inertia', 'Action and Reaction', 'Gravitation']],
     'hi'=>['q'=>'न्यूटन का गति का प्रथम नियम किस नियम के नाम से भी जाना जाता है?',
            'o'=>['त्वरण', 'जड़त्व', 'क्रिया-प्रतिक्रिया', 'गुरुत्वाकर्षण']]],
    ['s'=>'science', 'a'=>2,
     'en'=>['q'=>'Which inert gas is commonly filled in incandescent electric bulbs?',
            'o'=>['Oxygen', 'Nitrogen', 'Argon', 'Carbon dioxide']],
     'hi'=>['q'=>'तापदीप्त विद्युत बल्बों में सामान्यतः कौन-सी अक्रिय गैस भरी जाती है?',
            'o'=>['ऑक्सीजन', 'नाइट्रोजन', 'आर्गन', 'कार्बन डाइऑक्साइड']]],
    ['s'=>'science', 'a'=>0,
     'en'=>['q'=>'The chemical formula of common salt is:',
            'o'=>['NaCl', 'KCl', 'CaCl₂', 'NaOH']],
     'hi'=>['q'=>'साधारण नमक का रासायनिक सूत्र है:',
            'o'=>['NaCl', 'KCl', 'CaCl₂', 'NaOH']]],
    ['s'=>'science', 'a'=>1,
     'en'=>['q'=>'The pH value of pure water is:',
            'o'=>['0', '7', '14', '1']],
     'hi'=>['q'=>'शुद्ध जल का pH मान है:',
            'o'=>['0', '7', '14', '1']]],
    ['s'=>'science', 'a'=>1,
     'en'=>['q'=>'Which metal is in the liquid state at room temperature?',
            'o'=>['Iron', 'Mercury', 'Aluminium', 'Lead']],
     'hi'=>['q'=>'कमरे के तापमान पर कौन-सी धातु द्रव अवस्था में होती है?',
            'o'=>['लोहा', 'पारा', 'ऐलुमिनियम', 'सीसा']]],
    ['s'=>'science', 'a'=>1,
     'en'=>['q'=>'Which gas is released during photosynthesis?',
            'o'=>['Carbon dioxide', 'Oxygen', 'Nitrogen', 'Hydrogen']],
     'hi'=>['q'=>'प्रकाश संश्लेषण के दौरान कौन-सी गैस मुक्त होती है?',
            'o'=>['कार्बन डाइऑक्साइड', 'ऑक्सीजन', 'नाइट्रोजन', 'हाइड्रोजन']]],
    ['s'=>'science', 'a'=>2,
     'en'=>['q'=>'Which organelle is called the powerhouse of the cell?',
            'o'=>['Nucleus', 'Ribosome', 'Mitochondria', 'Golgi body']],
     'hi'=>['q'=>'किस कोशिकांग को कोशिका का ऊर्जा गृह (पावरहाउस) कहा जाता है?',
            'o'=>['केंद्रक', 'राइबोसोम', 'माइटोकॉन्ड्रिया', 'गॉल्जीकाय']]],
    ['s'=>'science', 'a'=>3,
     'en'=>['q'=>'Which vitamin is produced in human skin on exposure to sunlight?',
            'o'=>['Vitamin A', 'Vitamin B12', 'Vitamin C', 'Vitamin D']],
     'hi'=>['q'=>'सूर्य के प्रकाश के संपर्क में आने पर मानव त्वचा में कौन-सा विटामिन बनता है?',
            'o'=>['विटामिन A', 'विटामिन B12', 'विटामिन C', 'विटामिन D']]],
    ['s'=>'science', 'a'=>2,
     'en'=>['q'=>'Sound cannot travel through:',
            'o'=>['Water', 'Air', 'Vacuum', 'Steel']],
     'hi'=>['q'=>'ध्वनि किसमें से होकर नहीं चल सकती?',
            'o'=>['जल', 'वायु', 'निर्वात', 'इस्पात']]],
    ['s'=>'science', 'a'=>1,
     'en'=>['q'=>'The SI unit of force is:',
            'o'=>['Joule', 'Newton', 'Pascal', 'Watt']],
     'hi'=>['q'=>'बल का SI मात्रक है:',
            'o'=>['जूल', 'न्यूटन', 'पास्कल', 'वाट']]],
    ['s'=>'science', 'a'=>2,
     'en'=>['q'=>'Which element has the atomic number 1?',
            'o'=>['Helium', 'Oxygen', 'Hydrogen', 'Carbon']],
     'hi'=>['q'=>'किस तत्व की परमाणु संख्या 1 है?',
            'o'=>['हीलियम', 'ऑक्सीजन', 'हाइड्रोजन', 'कार्बन']]],
    ['s'=>'science', 'a'=>0,
     'en'=>['q'=>'Brass is an alloy of:',
            'o'=>['Copper and Zinc', 'Copper and Tin', 'Iron and Carbon', 'Lead and Tin']],
     'hi'=>['q'=>'पीतल किसकी मिश्रधातु है?',
            'o'=>['ताँबा और जस्ता', 'ताँबा और टिन', 'लोहा और कार्बन', 'सीसा और टिन']]],
    ['s'=>'science', 'a'=>2,
     'en'=>['q'=>'Which organ of the human body produces insulin?',
            'o'=>['Liver', 'Kidney', 'Pancreas', 'Heart']],
     'hi'=>['q'=>'मानव शरीर का कौन-सा अंग इंसुलिन का उत्पादन करता है?',
            'o'=>['यकृत', 'वृक्क', 'अग्न्याशय', 'हृदय']]],
    ['s'=>'science', 'a'=>0,
     'en'=>['q'=>'The speed of sound in air at room temperature is approximately:',
            'o'=>['343 m/s', '300 m/s', '500 m/s', '100 m/s']],
     'hi'=>['q'=>'कमरे के तापमान पर वायु में ध्वनि की चाल लगभग कितनी होती है?',
            'o'=>['343 मी/से', '300 मी/से', '500 मी/से', '100 मी/से']]],
    ['s'=>'science', 'a'=>1,
     'en'=>['q'=>'Rusting of iron is an example of:',
            'o'=>['Physical change', 'Chemical change', 'Nuclear reaction', 'No change']],
     'hi'=>['q'=>'लोहे में जंग लगना किसका उदाहरण है?',
            'o'=>['भौतिक परिवर्तन', 'रासायनिक परिवर्तन', 'नाभिकीय अभिक्रिया', 'कोई परिवर्तन नहीं']]],
    ['s'=>'science', 'a'=>2,
     'en'=>['q'=>'The number of chromosomes in a normal human body cell is:',
            'o'=>['23', '44', '46', '48']],
     'hi'=>['q'=>'सामान्य मानव शरीर कोशिका में गुणसूत्रों की संख्या होती है:',
            'o'=>['23', '44', '46', '48']]],
    ['s'=>'science', 'a'=>1,
     'en'=>['q'=>'Which type of lens is used to correct myopia (short-sightedness)?',
            'o'=>['Convex lens', 'Concave lens', 'Bifocal lens', 'Cylindrical lens']],
     'hi'=>['q'=>'निकट दृष्टि दोष (मायोपिया) को ठीक करने के लिए किस प्रकार के लेंस का प्रयोग किया जाता है?',
            'o'=>['उत्तल लेंस', 'अवतल लेंस', 'द्विफोकसी लेंस', 'बेलनाकार लेंस']]],
    ['s'=>'science', 'a'=>2,
     'en'=>['q'=>'Which gas is chiefly responsible for the greenhouse effect?',
            'o'=>['Oxygen', 'Nitrogen', 'Carbon dioxide', 'Hydrogen']],
     'hi'=>['q'=>'ग्रीनहाउस प्रभाव के लिए मुख्य रूप से कौन-सी गैस उत्तरदायी है?',
            'o'=>['ऑक्सीजन', 'नाइट्रोजन', 'कार्बन डाइऑक्साइड', 'हाइड्रोजन']]],
    ['s'=>'science', 'a'=>2,
     'en'=>['q'=>'An electric fuse works on the principle of the:',
            'o'=>['Chemical effect of current', 'Magnetic effect of current', 'Heating effect of current', 'Electromagnetic induction']],
     'hi'=>['q'=>'विद्युत फ्यूज किस सिद्धांत पर कार्य करता है?',
            'o'=>['धारा का रासायनिक प्रभाव', 'धारा का चुंबकीय प्रभाव', 'धारा का ऊष्मीय प्रभाव', 'विद्युतचुंबकीय प्रेरण']]],
    ['s'=>'science', 'a'=>3,
     'en'=>['q'=>'Which blood group is known as the universal donor?',
            'o'=>['A', 'B', 'AB', 'O']],
     'hi'=>['q'=>'किस रक्त समूह को सर्वदाता (यूनिवर्सल डोनर) कहा जाता है?',
            'o'=>['A', 'B', 'AB', 'O']]],
    ['s'=>'science', 'a'=>0,
     'en'=>['q'=>'According to Ohm\'s law, V is equal to:',
            'o'=>['I × R', 'I / R', 'I + R', 'I − R']],
     'hi'=>['q'=>'ओम के नियम के अनुसार, V किसके बराबर होता है?',
            'o'=>['I × R', 'I / R', 'I + R', 'I − R']]],
    ['s'=>'science', 'a'=>1,
     'en'=>['q'=>'Which cell organelle is responsible for protein synthesis?',
            'o'=>['Mitochondria', 'Ribosome', 'Lysosome', 'Nucleus']],
     'hi'=>['q'=>'प्रोटीन संश्लेषण के लिए कौन-सा कोशिकांग उत्तरदायी है?',
            'o'=>['माइटोकॉन्ड्रिया', 'राइबोसोम', 'लाइसोसोम', 'केंद्रक']]],
    ['s'=>'science', 'a'=>2,
     'en'=>['q'=>'The direct conversion of a solid into a gas is called:',
            'o'=>['Evaporation', 'Condensation', 'Sublimation', 'Deposition']],
     'hi'=>['q'=>'ठोस का सीधे गैस में परिवर्तित होना क्या कहलाता है?',
            'o'=>['वाष्पीकरण', 'संघनन', 'ऊर्ध्वपातन', 'निक्षेपण']]],
    ['s'=>'science', 'a'=>2,
     'en'=>['q'=>'Which acid is present in the human stomach?',
            'o'=>['Sulphuric acid', 'Nitric acid', 'Hydrochloric acid', 'Acetic acid']],
     'hi'=>['q'=>'मानव आमाशय में कौन-सा अम्ल पाया जाता है?',
            'o'=>['सल्फ्यूरिक अम्ल', 'नाइट्रिक अम्ल', 'हाइड्रोक्लोरिक अम्ल', 'एसिटिक अम्ल']]],
];

$SECTIONS = [
    'math'      => ['en' => 'Mathematics',                     'hi' => 'गणित',                            'short' => 'Maths',     'color' => '#1677F1'],
    'reasoning' => ['en' => 'General Intelligence & Reasoning', 'hi' => 'सामान्य बुद्धिमत्ता एवं तर्कशक्ति', 'short' => 'Reasoning', 'color' => '#7C4DFF'],
    'science'   => ['en' => 'General Science',                  'hi' => 'सामान्य विज्ञान',                  'short' => 'Science',   'color' => '#08A899'],
];

$DURATION  = 60;                 // minutes
$NEGATIVE  = 1 / 3;              // marks lost per wrong answer
$QUALIFY   = ['UR / EWS' => 40, 'OBC (NCL)' => 30, 'SC' => 30, 'ST' => 25];
$CANDIDATE = auth()->check() ? auth()->user()->name : 'Angelin';

$TOTAL = count($Q);

// Question numbers grouped by section, e.g. ['math' => [1,…,25], …].
$RANGES = array_fill_keys(array_keys($SECTIONS), []);
foreach ($Q as $i => $q) {
    $RANGES[$q['s']][] = $i + 1;
}

/* ─────────────────────────── ANSWER-KEY BOUNDARY ───────────────────────────
   $PLAYER is the question text with the answer key stripped.
   $KEY    is the answer key on its own.

   Right now BOTH are handed to the browser, because the whole test runs
   client-side in this single file. To lock the paper down later: send only
   $PLAYER, keep $KEY in a controller, and score a POSTed answer sheet there.
   ─────────────────────────────────────────────────────────────────────────── */
$PLAYER = array_map(static fn ($q) => ['s' => $q['s'], 'en' => $q['en'], 'hi' => $q['hi']], $Q);
$KEY    = array_column($Q, 'a');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>RRB ALP 2026 — CBT 1 (First Stage)</title>

<style>
/* ══════════════════════════ Tokens ══════════════════════════ */
:root{
    --teal:#17A2B8; --soft:#A9DCEC; --panel:#DCEEF7; --tab:#1E7A8C;
    --line:#D5DBE0; --text:#212529; --muted:#6C757D; --link:#0B5FA5; --danger:#DC3545;
    --green:#22A45D; --red:#B4322C; --purple:#7C3E9B;
    --topbar:66px; --footer:64px; --side:360px;
}
*{margin:0;padding:0;box-sizing:border-box}
html,body{height:100%}
body{font-family:'Segoe UI',-apple-system,BlinkMacSystemFont,Roboto,Arial,sans-serif;
     font-size:15px;line-height:1.5;color:var(--text);background:#fff;-webkit-font-smoothing:antialiased}
.hi-text{font-size:15.5px;line-height:1.75}
button{font-family:inherit}
a{color:var(--link)}
[hidden]{display:none !important}

/* ══════════════════════════ Buttons ══════════════════════════ */
.btn{border:0;border-radius:4px;padding:10px 18px;font-size:14px;font-weight:600;cursor:pointer;
     color:#fff;background:var(--teal);transition:filter .12s;text-decoration:none;display:inline-block}
.btn:hover:not(:disabled){filter:brightness(1.07)}
.btn:disabled{opacity:.55;cursor:not-allowed}
.btn.soft{background:var(--soft);color:#123}
.btn.wide{min-width:150px;text-align:center}

/* ══════════════════════════ Instruction screens ══════════════════════════ */
.instr{display:flex;height:100vh;overflow:hidden}
.instr__main{flex:1;min-width:0;display:flex;flex-direction:column;border-right:1px solid var(--line)}
.instr__scroll{flex:1;overflow-y:auto;padding:26px 30px 20px}
.instr__foot{flex-shrink:0;border-top:1px solid var(--line);background:#fff;padding:12px 26px;
     display:flex;align-items:center;justify-content:space-between;gap:14px}
.instr__aside{width:300px;flex-shrink:0;background:#F4F9FC;display:flex;flex-direction:column;
     align-items:center;padding-top:34px}
.avatar{width:112px;height:112px;border-radius:50%;background:#5D4037;color:#fff;display:grid;
     place-items:center;font-size:52px;font-weight:600;margin-bottom:18px}
.cand{font-size:26px;text-align:center;padding:0 12px}

.title{font-size:30px;font-weight:700;text-align:center;margin-bottom:22px}
.meta{display:flex;justify-content:space-between;font-weight:700;margin-bottom:18px}
.lead{font-weight:700;margin-bottom:12px}
.list{margin:0 0 18px 22px}
.list li{margin-bottom:11px}
.list ol,.list ul{margin:9px 0 9px 22px}
.note{color:var(--danger)}
.h3{font-weight:700;font-size:16px;margin:20px 0 10px}

.legend{list-style:none;margin:12px 0 18px}
.legend li{display:flex;align-items:center;gap:12px;margin-bottom:10px}
.chip{width:30px;height:26px;flex-shrink:0;border:1px solid #6C757D;background:#fff;border-radius:3px}
.chip.answered{background:var(--green);border-color:var(--green);border-radius:3px 3px 8px 8px}
.chip.notans{background:var(--red);border-color:var(--red);border-radius:8px 8px 3px 3px}
.chip.marked{background:var(--purple);border-color:var(--purple);border-radius:50%}
.chip.markans{background:var(--purple);border-color:var(--purple);border-radius:50%;position:relative}
.chip.markans::after{content:"✓";position:absolute;right:-4px;bottom:-4px;width:15px;height:15px;
     border-radius:50%;background:var(--green);color:#fff;font-size:10px;line-height:15px;text-align:center}

.langrow{display:flex;align-items:center;gap:10px;flex-wrap:wrap;padding:14px 26px;border-top:1px solid var(--line)}
.langrow select,.viewin select{padding:5px 8px;font-size:14px;font-family:inherit;
     border:1px solid #767676;border-radius:2px;background:#fff}
.declare{display:flex;align-items:flex-start;gap:9px;padding:0 26px 4px}
.declare input{width:16px;height:16px;margin-top:3px;flex-shrink:0;accent-color:var(--link)}
.declare label{font-weight:700;font-size:14.5px}

/* ══════════════════════════ Test window ══════════════════════════ */
.app{height:100vh;display:flex;flex-direction:column;overflow:hidden}
.top{height:var(--topbar);flex-shrink:0;border-bottom:1px solid var(--line);display:flex;
     align-items:center;justify-content:space-between;gap:16px;padding:0 20px}
.top__name{font-size:16px;font-weight:600}
.clock{display:flex;align-items:center;gap:10px;border:1px solid var(--line);border-radius:5px;padding:7px 14px}
.clock b{font-size:16px}
.clock span{background:#E9ECEF;border-radius:3px;padding:3px 7px;font-size:16px;font-weight:700;
     font-variant-numeric:tabular-nums;min-width:31px;text-align:center;display:inline-block}
.clock.warn span{background:#FFE3E3;color:var(--red)}
.fsbtn{border:1px solid var(--teal);background:#fff;color:var(--teal);border-radius:4px;
     padding:9px 15px;font-size:14px;font-weight:600;cursor:pointer}

.sections{flex-shrink:0;border-bottom:1px solid var(--line);display:flex;padding-left:20px}
.sections__label{display:flex;align-items:center;font-size:13.5px;color:#495057;padding-right:22px;
     border-right:1px solid var(--line);margin-right:14px}
.sectab{border:0;background:#E9F3F6;color:#1E7A8C;font-size:14px;font-weight:600;padding:11px 20px;
     cursor:pointer;border-radius:4px 4px 0 0;margin:6px 4px 0 0;white-space:nowrap}
.sectab[aria-selected="true"]{background:var(--tab);color:#fff}

.body{flex:1;display:flex;min-height:0}
.qcol{flex:1;min-width:0;display:flex;flex-direction:column}
.qhead{flex-shrink:0;display:flex;align-items:center;justify-content:space-between;gap:18px;
     padding:14px 22px;border-bottom:1px solid #EEF1F3;flex-wrap:wrap}
.qno{font-size:17px;font-weight:700}
.qstats{display:flex;align-items:center;gap:22px;font-size:13px;color:#495057}
.qstats b{display:block;font-size:12.5px;color:var(--text)}
.mpos{background:var(--green);color:#fff;border-radius:11px;padding:2px 9px;font-weight:700;font-size:12.5px}
.mneg{background:var(--red);color:#fff;border-radius:11px;padding:2px 9px;font-weight:700;font-size:12.5px}
.report{border:0;background:none;color:var(--muted);font-size:13.5px;cursor:pointer}

.qscroll{flex:1;overflow-y:auto;padding:22px}
.qtext{font-size:17px;line-height:1.65;margin-bottom:22px;max-width:900px}
.opts{list-style:none;max-width:900px}
.opts li{margin-bottom:14px}
.opt{display:flex;align-items:flex-start;gap:12px;cursor:pointer;font-size:16px;line-height:1.55;padding:2px 0}
.opt input{width:16px;height:16px;margin-top:4px;flex-shrink:0;accent-color:var(--link)}

.foot{height:var(--footer);flex-shrink:0;border-top:1px solid var(--line);display:flex;
     align-items:center;justify-content:space-between;gap:12px;padding:0 18px}
.foot__left{display:flex;gap:10px}

.side{width:var(--side);flex-shrink:0;background:var(--panel);border-left:1px solid #BFD8E5;
     display:flex;flex-direction:column}
.side__who{display:flex;align-items:center;gap:11px;padding:12px 16px;background:#fff;border-bottom:1px solid #CFE2EC}
.side__who .av{width:38px;height:38px;border-radius:50%;background:#5D4037;color:#fff;display:grid;
     place-items:center;font-size:18px;font-weight:600;flex-shrink:0}
.counts{display:grid;grid-template-columns:1fr 1fr;gap:7px 12px;padding:12px 16px;background:#fff;
     border-bottom:1px solid #CFE2EC;font-size:12.5px}
.counts span{display:flex;align-items:center;gap:7px}
.cnt{min-width:22px;height:20px;border-radius:3px;color:#fff;font-weight:700;font-size:11.5px;
     display:grid;place-items:center;padding:0 5px}
.cnt.a{background:var(--green)} .cnt.m{background:var(--purple)}
.cnt.na{background:var(--red)}  .cnt.nv{background:#fff;color:var(--text);border:1px solid #6C757D}
.side__sec{padding:9px 16px;background:#B9DCEC;font-size:14px;font-weight:600}
.palette{flex:1;overflow-y:auto;padding:14px 16px;display:grid;grid-template-columns:repeat(5,1fr);
     gap:9px;align-content:start}
.pal{height:36px;border:1px solid #6C757D;background:#fff;color:var(--text);border-radius:4px;
     font-size:14px;font-weight:600;cursor:pointer;display:grid;place-items:center;position:relative}
.pal:hover{transform:scale(1.06)}
.pal.current{outline:2px solid var(--link);outline-offset:1px}
.pal[data-state="answered"]{background:var(--green);border-color:var(--green);color:#fff;border-radius:4px 4px 13px 13px}
.pal[data-state="not-answered"]{background:var(--red);border-color:var(--red);color:#fff;border-radius:13px 13px 4px 4px}
.pal[data-state="marked"],.pal[data-state="marked-answered"]{background:var(--purple);border-color:var(--purple);color:#fff;border-radius:50%}
.pal[data-state="marked-answered"]::after{content:"✓";position:absolute;right:-3px;bottom:-3px;width:16px;
     height:16px;border-radius:50%;background:var(--green);color:#fff;font-size:10px;line-height:16px;text-align:center}
.side__foot{flex-shrink:0;padding:12px 16px;display:grid;gap:9px;grid-template-columns:1fr 1fr;border-top:1px solid #CFE2EC}
.side__foot .btn.soft{padding:11px 8px;font-size:13.5px}
.side__foot .submit{grid-column:1 / -1;padding:13px;font-size:15px}

/* ══════════════════════════ Modals ══════════════════════════ */
.modal{position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:400;display:none;
     align-items:flex-start;justify-content:center;padding:64px 16px}
.modal.open{display:flex}
.modal__box{background:#fff;border-radius:6px;width:100%;max-width:780px;box-shadow:0 12px 40px rgba(0,0,0,.3);
     max-height:calc(100vh - 128px);display:flex;flex-direction:column}
.modal__head{padding:16px;text-align:center;font-size:15px;border-bottom:1px solid #EEF1F3}
.modal__body{padding:18px;overflow:auto}
.modal__foot{padding:14px 18px;border-top:1px solid var(--line);display:flex;justify-content:flex-end;gap:10px}
.table{width:100%;border-collapse:collapse;font-size:14px}
.table th{background:var(--teal);color:#fff;font-weight:700;padding:14px 10px;text-align:center;border:1px solid var(--teal)}
.table td{padding:14px 10px;text-align:center;border:1px solid var(--line)}
.table td:first-child{text-align:left}

/* ══════════════════════════ Result ══════════════════════════ */
.page{max-width:1080px;margin:0 auto;padding:26px 20px 60px}
.hero{background:linear-gradient(135deg,#4B3BD6,#6D28D9);color:#fff;border-radius:16px;
     padding:30px;text-align:center;margin-bottom:22px}
.hero h2{font-size:24px;font-weight:800;margin-bottom:22px}
.cards{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
.card{background:#fff;border-radius:13px;padding:20px;color:var(--text);text-align:left}
.card h4{font-size:13px;font-weight:800;color:var(--muted);text-transform:uppercase;letter-spacing:.4px;margin-bottom:12px}
.big{font-size:32px;font-weight:800;line-height:1.1}
.sub{font-size:12px;color:var(--muted);margin-top:3px}
.split{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.dot{display:flex;align-items:center;gap:9px;font-size:13.5px;margin-bottom:9px}
.dot i{width:11px;height:11px;border-radius:50%;flex-shrink:0}
.dot b{margin-left:auto;font-size:15px}
.panel{background:#fff;border:1px solid #EBEDF5;border-radius:14px;padding:20px;margin-bottom:18px;
     box-shadow:0 1px 2px rgba(20,20,50,.04),0 8px 24px rgba(20,20,50,.05)}
.panel h3{font-size:16px;font-weight:800;margin-bottom:14px}
.secrow{margin-bottom:15px}
.secrow__top{display:flex;justify-content:space-between;align-items:baseline;font-size:13.5px;margin-bottom:6px;gap:12px}
.bar{height:8px;border-radius:99px;background:#EEF0F6;overflow:hidden}
.bar i{display:block;height:100%;border-radius:99px}
.qual{display:grid;grid-template-columns:repeat(2,1fr);gap:11px}
.qual > div{padding:12px 14px;border-radius:10px;font-size:13.5px;border-left:4px solid #EBEDF5;background:#F7F8FC}
.qual .yes{border-left-color:var(--green);background:#EAF9F1}
.qual .no{border-left-color:var(--red);background:#FDEEF2}
.warnbox{background:#FFF6E8;border-left:4px solid #F7931E;border-radius:10px;padding:14px 16px;
     font-size:13.5px;line-height:1.7;margin-bottom:18px}
.infobox{background:#EEF3FF;border-left:4px solid #1677F1;border-radius:10px;padding:14px 16px;
     font-size:13.5px;line-height:1.7;margin-bottom:18px}
.actions{display:flex;gap:11px;flex-wrap:wrap}
.btn.indigo{background:#5A4CF0}
.btn.ghost{background:#fff;color:#5A4CF0;border:1.5px solid #5A4CF0}

/* ══════════════════════════ Review ══════════════════════════ */
.rvbar{display:flex;gap:9px;flex-wrap:wrap;align-items:center;margin-bottom:18px}
.fchip{border:1.5px solid #EBEDF5;background:#fff;border-radius:99px;padding:8px 15px;font-size:13px;
     font-weight:700;cursor:pointer;color:var(--text)}
.fchip.on{background:#5A4CF0;border-color:#5A4CF0;color:#fff}
.fchip .n{opacity:.7;font-weight:600}
.rvcard{background:#fff;border:1px solid #EBEDF5;border-left-width:5px;border-radius:12px;
     padding:18px 20px;margin-bottom:13px}
.rvcard.correct{border-left-color:var(--green)}
.rvcard.wrong{border-left-color:var(--red)}
.rvcard.skipped{border-left-color:#C9CDDB}
.rvcard__top{display:flex;justify-content:space-between;gap:14px;align-items:flex-start;margin-bottom:12px}
.rvcard__q{font-size:15px;font-weight:700;line-height:1.6}
.tag{font-size:11px;font-weight:800;padding:4px 10px;border-radius:99px;white-space:nowrap;flex-shrink:0}
.rvopt{padding:9px 13px;border-radius:8px;border:1px solid #EBEDF5;background:#F8F9FC;font-size:14px;
     margin-bottom:7px;display:flex;gap:9px;align-items:flex-start}
.rvopt.key{border-color:var(--green);background:#EAF9F1}
.rvopt.mine{border-color:var(--red);background:#FDEEF2}
.rvopt .flag{margin-left:auto;font-size:11.5px;font-weight:800;white-space:nowrap}

/* ══════════════════════════ Toast ══════════════════════════ */
.toast{position:fixed;bottom:26px;left:50%;transform:translateX(-50%) translateY(8px);background:#212529;
     color:#fff;padding:11px 20px;border-radius:6px;font-size:14px;font-weight:600;z-index:500;
     opacity:0;pointer-events:none;transition:.2s}
.toast.show{opacity:1;transform:translateX(-50%)}

/* ══════════════════════════ Narrow windows ══════════════════════════ */
@media(max-width:1100px){ :root{--side:290px} .palette{grid-template-columns:repeat(4,1fr)} }
@media(max-width:900px){
    .cards,.split,.qual{grid-template-columns:1fr}
    .body{flex-direction:column}
    .side{width:100%;border-left:0;border-top:1px solid #BFD8E5;max-height:44vh}
    .instr{flex-direction:column;height:auto;overflow:visible}
    .instr__aside{width:100%;flex-direction:row;gap:16px;padding:16px;justify-content:center}
    .avatar{width:56px;height:56px;font-size:26px;margin:0}
    .cand{font-size:19px}
}
</style>
</head>
<body>

{{-- ══════════════════════════ SCREEN 1 · General instructions ══════════════════════════ --}}
<div class="instr" id="scrGeneral">
    <div class="instr__main">
        <div class="instr__scroll">
            <p class="lead" style="font-size:17px">General Instructions:</p>

            <ol class="list">
                <li>
                    The clock will be set at the server. The countdown timer at the top right corner of screen
                    will display the remaining time available for you to complete the examination. When the timer
                    reaches zero, the examination will end by itself. You need not terminate the examination or
                    submit your paper.
                </li>
                <li>
                    The Question Palette displayed on the right side of screen will show the status of each
                    question using one of the following symbols:

                    <ul class="legend">
                        <li><span class="chip"></span> You have not visited the question yet.</li>
                        <li><span class="chip notans"></span> You have not answered the question.</li>
                        <li><span class="chip answered"></span> You have answered the question.</li>
                        <li><span class="chip marked"></span> You have NOT answered the question, but have marked the question for review.</li>
                        <li><span class="chip markans"></span> You have answered the question, but marked it for review.</li>
                    </ul>

                    <p>
                        The <b>Mark For Review</b> status for a question simply indicates that you would like to
                        look at that question again. If a question is answered, but marked for review, then the
                        answer will be considered for evaluation unless the status is modified by the candidate.
                    </p>
                </li>
            </ol>

            <p class="h3">Navigating to a Question:</p>
            <ol class="list" start="3">
                <li>
                    To answer a question, do the following:
                    <ol type="1">
                        <li>Click on the question number in the Question Palette at the right of your screen to go to that numbered question directly. Note that using this option does NOT save your answer to the current question.</li>
                        <li>Click on <b>Save &amp; Next</b> to save your answer for the current question and then go to the next question.</li>
                        <li>Click on <b>Mark for Review &amp; Next</b> to save your answer for the current question and also mark it for review, and then go to the next question.</li>
                    </ol>
                </li>
            </ol>

            <p style="margin-bottom:14px">
                Note that your answer for the current question will not be saved, if you navigate to another
                question directly by clicking on a question number without saving the answer to the previous question.
            </p>
            <p style="margin-bottom:14px">
                You can view all the questions by clicking on the <b>Question Paper</b> button.
                <span class="note">This feature is provided, so that if you want you can just see the entire question paper at a glance.</span>
            </p>

            <p class="h3">Answering a Question:</p>
            <ol class="list" start="4">
                <li>
                    Procedure for answering a multiple choice (MCQ) type question:
                    <ol type="1">
                        <li>Choose one answer from the 4 options (A, B, C, D) given below the question, click on the bubble placed before the chosen option.</li>
                        <li>To deselect your chosen answer, click on the bubble of the chosen option again or click on the <b>Clear Response</b> button.</li>
                        <li>To change your chosen answer, click on the bubble of another option.</li>
                        <li>To save your answer, you MUST click on the <b>Save &amp; Next</b>.</li>
                    </ol>
                </li>
                <li>
                    Procedure for answering a numerical answer type question:
                    <ol type="1">
                        <li>To enter a number as your answer, use the virtual numerical keypad.</li>
                        <li>A fraction (e.g. -0.3 or -.3) can be entered as an answer with or without "0" before the decimal point. <span class="note">As many as four decimal points, e.g. 12.5435 or 0.003 or -932.6711 or 12.82 can be entered.</span></li>
                        <li>To clear your answer, click on the <b>Clear Response</b> button.</li>
                        <li>To save your answer, you MUST click on the <b>Save &amp; Next</b>.</li>
                    </ol>
                </li>
                <li>To mark a question for review, click on the <b>Mark for Review &amp; Next</b> button. If an answer is selected (for MCQ/MCAQ) entered (for numerical answer type) for a question that is <b>Marked for Review</b>, that answer will be considered in the evaluation unless the status is modified by the candidate.</li>
                <li>To change your answer to a question that has already been answered, first select that question for answering and then follow the procedure for answering that type of question.</li>
                <li>Note that ONLY Questions for which answers are <b>saved</b> or <b>marked for review after answering</b> will be considered for evaluation.</li>
                <li>Sections in this question paper are displayed on the top bar of the screen. Questions in a Section can be viewed by clicking on the name of that Section. The Section you are currently viewing will be highlighted.</li>
                <li>After clicking the <b>Save &amp; Next</b> button for the last question in a Section, you will automatically be taken to the first question of the next Section in sequence.</li>
                <li>You can move the mouse cursor over the name of a Section to view the answering status for that Section.</li>
            </ol>
        </div>

        <div class="instr__foot">
            <a href="{{ url('/') }}" style="text-decoration:none;font-weight:600">← Go to Tests</a>
            <button type="button" class="btn soft wide" onclick="show('scrInstructions')">Next</button>
        </div>
    </div>

    <aside class="instr__aside">
        <div class="avatar">{{ mb_strtoupper(mb_substr($CANDIDATE, 0, 1)) }}</div>
        <div class="cand">{{ $CANDIDATE }}</div>
    </aside>
</div>

{{-- ══════════════════════════ SCREEN 2 · Instructions + declaration ══════════════════════════ --}}
<div class="instr" id="scrInstructions" hidden>
    <div class="instr__main">
        <div class="instr__scroll">
            <h1 class="title">RRB ALP 2026 — CBT 1 (First Stage): Full Live Test</h1>

            <div class="meta">
                <span>Duration: {{ $DURATION }} Mins</span>
                <span>Maximum Marks: {{ $TOTAL }}</span>
            </div>

            <p class="lead">Read the following instructions carefully.</p>

            <ol class="list">
                <li>
                    The test contains {{ count($SECTIONS) }} sections having a total of {{ $TOTAL }} questions.
                    <ul style="margin:8px 0 0 20px">
                        @foreach ($SECTIONS as $k => $meta)
                            <li>{{ $meta['en'] }} — {{ count($RANGES[$k]) }} questions</li>
                        @endforeach
                    </ul>
                </li>
                <li>Each question has 4 options out of which only one is correct.</li>
                <li>You have to finish the test in {{ $DURATION }} minutes.</li>
                <li>Try not to guess the answer as there is negative marking.</li>
                <li>You will be awarded <b>1 mark</b> for each correct answer and <b>{{ number_format($NEGATIVE, 2) }} marks</b> will be deducted for each wrong answer (1/3rd of a mark).</li>
                <li>There is no penalty for the questions that you have not attempted.</li>
                <li>Once you start the test, you will not be allowed to reattempt it. Make sure that you complete the test before you submit the test and/or close the browser.</li>
                <li>CBT 1 is a <b>screening test only</b> — the marks scored here are not counted in the final merit list. Candidates are shortlisted for CBT 2 on normalised marks, up to 15 times the number of vacancies.</li>
            </ol>
        </div>

        <div class="langrow">
            <label for="lang"><b>Choose your default language:</b></label>
            <select id="lang">
                <option value="">-- Select --</option>
                <option value="en">English</option>
                <option value="hi">हिन्दी</option>
            </select>
        </div>

        <div style="padding:0 26px 12px">
            <p class="note">Please note all questions will appear in your default language. This language can be changed for a particular question later on</p>
        </div>

        <div style="padding:0 26px 10px"><b>Declaration:</b></div>

        <div class="declare">
            <input type="checkbox" id="declare">
            <label for="declare">I have understood and agree to all the instructions.</label>
        </div>

        <div class="instr__foot">
            <button type="button" class="btn soft wide" onclick="show('scrGeneral')">Previous</button>
            <button type="button" class="btn wide" id="btnReady" disabled>I am ready to begin</button>
        </div>
    </div>

    <aside class="instr__aside">
        <div class="avatar">{{ mb_strtoupper(mb_substr($CANDIDATE, 0, 1)) }}</div>
        <div class="cand">{{ $CANDIDATE }}</div>
    </aside>
</div>

{{-- ══════════════════════════ SCREEN 3 · The test ══════════════════════════ --}}
<div class="app" id="scrTest" hidden>

    <header class="top">
        <div class="top__name">RRB ALP 2026 — CBT 1 (First Stage): Full Live Test</div>
        <div class="clock" id="clock">
            <b>Time Left</b>
            <span id="clkH">00</span>:<span id="clkM">00</span>:<span id="clkS">00</span>
        </div>
        <button type="button" class="fsbtn" id="btnFs">Switch Full Screen</button>
    </header>

    <nav class="sections" role="tablist">
        <span class="sections__label">SECTIONS</span>
        @foreach ($SECTIONS as $k => $meta)
            <button type="button" class="sectab" role="tab" data-section="{{ $k }}"
                    data-first="{{ $RANGES[$k][0] ?? 1 }}"
                    aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                    title="{{ $meta['en'] }} — {{ count($RANGES[$k]) }} questions">{{ $meta['short'] }}</button>
        @endforeach
    </nav>

    <div class="body">
        <main class="qcol">
            <div class="qhead">
                <div class="qno">Question No. <span id="qNo">1</span></div>
                <div class="qstats">
                    <div>Marks<div style="margin-top:3px"><span class="mpos">+1</span> <span class="mneg">-0.33</span></div></div>
                    <div>Time<b id="qTime">00:00</b></div>
                    <div class="viewin">
                        <label for="viewIn">View in</label>
                        <select id="viewIn">
                            <option value="en">English</option>
                            <option value="hi">हिन्दी</option>
                        </select>
                    </div>
                    <button type="button" class="report" id="btnReport">⚠ Report</button>
                </div>
            </div>

            <div class="qscroll">
                <p class="qtext" id="qText"></p>
                <ul class="opts" id="qOpts">
                    @for ($i = 0; $i < 4; $i++)
                        <li><label class="opt"><input type="radio" name="choice" value="{{ $i }}"><span data-opt="{{ $i }}"></span></label></li>
                    @endfor
                </ul>
            </div>

            <footer class="foot">
                <div class="foot__left">
                    <button type="button" class="btn soft" id="btnMark">Mark for Review &amp; Next</button>
                    <button type="button" class="btn soft" id="btnClear">Clear Response</button>
                </div>
                <button type="button" class="btn soft" id="btnSave" style="min-width:130px">Save &amp; Next</button>
            </footer>
        </main>

        <aside class="side">
            <div class="side__who">
                <div class="av">{{ mb_strtoupper(mb_substr($CANDIDATE, 0, 1)) }}</div>
                <b>{{ $CANDIDATE }}</b>
            </div>

            <div class="counts">
                <span><i class="cnt a" id="cAns">0</i> Answered</span>
                <span><i class="cnt m" id="cMark">0</i> Marked</span>
                <span><i class="cnt nv" id="cNV">{{ $TOTAL }}</i> Not Visited</span>
                <span><i class="cnt m" id="cMarkAns">0</i> Marked and answered</span>
                <span><i class="cnt na" id="cNA">0</i> Not Answered</span>
            </div>

            <div class="side__sec">SECTION : <span id="secName"></span></div>
            <div class="palette" id="palette"></div>

            <div class="side__foot">
                <button type="button" class="btn soft" onclick="openModal('paperModal')">Question Paper</button>
                <button type="button" class="btn soft" onclick="openModal('instrModal')">Instructions</button>
                <button type="button" class="btn submit" id="btnSubmit">Submit Test</button>
            </div>
        </aside>
    </div>
</div>

{{-- ══════════════════════════ SCREEN 4 · Result ══════════════════════════ --}}
<div class="page" id="scrResult" hidden>
    <div class="warnbox" id="autoNote" hidden style="border-left-color:var(--red);background:#FDEEF2">
        ⏰ <b>Time expired.</b> Your test was submitted automatically when the clock reached zero.
        Everything you had saved up to that moment has been marked.
    </div>

    <div class="hero">
        <h2>Thank you for attempting RRB ALP 2026 — CBT 1 (First Stage): Full Live Test</h2>
        <div class="cards">
            <div class="card">
                <h4>Score</h4>
                <div class="big" id="rScore">0.00<span style="font-size:18px;color:var(--muted)">/{{ $TOTAL }}</span></div>
                <div class="sub" id="rScoreSub"></div>
            </div>
            <div class="card">
                <h4>Attempts</h4>
                <div class="split">
                    <div><div class="big" id="rAttempted">0</div><div class="sub">of {{ $TOTAL }} questions</div></div>
                    <div><div class="big" id="rAccuracy">0%</div><div class="sub">accuracy (of attempted)</div></div>
                </div>
                <div class="sub" style="margin-top:12px" id="rSpeed"></div>
            </div>
            <div class="card">
                <h4>Breakdown</h4>
                <div class="dot"><i style="background:var(--green)"></i> Correct <b id="rCorrect">0</b></div>
                <div class="dot"><i style="background:var(--red)"></i> Incorrect <b id="rWrong">0</b></div>
                <div class="dot"><i style="background:#C9CDDB"></i> Skipped <b id="rSkipped">0</b></div>
            </div>
        </div>
    </div>

    <div class="panel"><h3>Section-wise performance</h3><div id="rSections"></div></div>
    <div class="panel"><h3>Qualifying status by category</h3><div class="qual" id="rQual"></div></div>

    <div class="warnbox" id="rPenalty"></div>

    <div class="infobox">
        <b>Remember what CBT 1 actually is.</b> It is a screening test. These marks decide only whether you are
        shortlisted for CBT 2 — they are <b>not</b> carried into the final merit list. Shortlisting is done on
        normalised marks, up to 15 times the number of vacancies.
        <div style="margin-top:8px;color:var(--muted)">No rank is shown. A rank needs every other candidate's attempt stored in a database.</div>
    </div>

    <div class="actions">
        <button type="button" class="btn indigo" onclick="showReview()">Review answers</button>
        <button type="button" class="btn ghost" onclick="location.reload()">Retake test</button>
    </div>
</div>

{{-- ══════════════════════════ SCREEN 5 · Answer review ══════════════════════════ --}}
<div class="page" id="scrReview" hidden>
    <h1 style="font-size:24px;font-weight:800;margin-bottom:4px">Answer Review</h1>
    <p style="color:var(--muted);margin-bottom:20px">RRB ALP 2026 — CBT 1 (First Stage): Full Live Test</p>

    <div class="rvbar" id="rvBar"></div>
    <div id="rvList"></div>
    <div id="rvEmpty" hidden style="padding:40px;text-align:center;color:var(--muted)">No questions match this filter.</div>
</div>

{{-- ══════════════════════════ Modals ══════════════════════════ --}}
<div class="modal" id="submitModal">
    <div class="modal__box">
        <div class="modal__head">Submit your test</div>
        <div class="modal__body">
            <table class="table">
                <thead><tr>
                    <th style="text-align:left">Section</th><th>No. of questions</th><th>Answered</th>
                    <th>Not Answered</th><th>Marked for Review</th><th>Not Visited</th>
                </tr></thead>
                <tbody id="summaryBody"></tbody>
            </table>
        </div>
        <div class="modal__foot">
            <button type="button" class="btn soft" onclick="closeModal('submitModal')">Close</button>
            <button type="button" class="btn" id="btnSubmitConfirm">Submit</button>
        </div>
    </div>
</div>

<div class="modal" id="paperModal">
    <div class="modal__box" style="max-width:900px">
        <div class="modal__head"><b>Question Paper</b></div>
        <div class="modal__body" id="paperBody"></div>
        <div class="modal__foot"><button type="button" class="btn soft" onclick="closeModal('paperModal')">Close</button></div>
    </div>
</div>

<div class="modal" id="instrModal">
    <div class="modal__box">
        <div class="modal__head"><b>Instructions</b></div>
        <div class="modal__body">
            <ul class="legend">
                <li><span class="chip"></span> You have not visited the question yet.</li>
                <li><span class="chip notans"></span> You have not answered the question.</li>
                <li><span class="chip answered"></span> You have answered the question.</li>
                <li><span class="chip marked"></span> You have NOT answered the question, but have marked it for review.</li>
                <li><span class="chip markans"></span> You have answered the question, but marked it for review.</li>
            </ul>
            <p style="margin-bottom:10px">Your answer is recorded only when you click <b>Save &amp; Next</b> or <b>Mark for Review &amp; Next</b>. Clicking a number in the palette moves you without saving.</p>
            <p><b>1 mark</b> for each correct answer, <b>−1/3</b> for each wrong answer, nothing for questions you leave blank.</p>
        </div>
        <div class="modal__foot"><button type="button" class="btn soft" onclick="closeModal('instrModal')">Close</button></div>
    </div>
</div>

<div class="toast" id="toast"></div>

<script>
(function () {
'use strict';

/* ═══════════════════════ Data handed over by Blade ═══════════════════════ */
var Q        = @json($PLAYER);          // question text only
var KEY      = @json($KEY);             // ← the ANSWER-KEY BOUNDARY, see the top of this file
var SECTIONS = @json($SECTIONS);
var RANGES   = @json($RANGES);
var QUALIFY  = @json($QUALIFY);
var TOTAL    = {{ $TOTAL }};
var DURATION = {{ $DURATION }} * 60;    // seconds
var NEG      = {{ $NEGATIVE }};

/* ═══════════════════════ State ═══════════════════════
   saved   — index => option index. ONLY what was actually saved.
   pending — the radio selection on screen, not yet saved.
   Those two being different is the whole point: in a real CBT, selecting a
   bubble and then jumping away from the question throws the selection away.
   ═════════════════════════════════════════════════════ */
var saved = {}, marked = {}, visited = {}, qSeconds = {};
var pending = null, current = 0, defLang = 'en', curLang = 'en';
var remaining = DURATION, timer = null, finished = false, result = null;

/* ═══════════════════════ Tiny helpers ═══════════════════════ */
function $(id) { return document.getElementById(id); }
function pad(n) { return String(n).padStart(2, '0'); }
function has(o, k) { return Object.prototype.hasOwnProperty.call(o, k); }
function esc(t) {
    return String(t).replace(/[&<>"']/g, function (c) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
}
function toast(msg) {
    var t = $('toast');
    t.textContent = msg;
    t.classList.add('show');
    clearTimeout(window.__t);
    window.__t = setTimeout(function () { t.classList.remove('show'); }, 2600);
}

var SCREENS = ['scrGeneral', 'scrInstructions', 'scrTest', 'scrResult', 'scrReview'];
function show(id) {
    SCREENS.forEach(function (s) { $(s).hidden = (s !== id); });
    window.scrollTo(0, 0);
}
window.show = show;

function openModal(id) { $(id).classList.add('open'); }
function closeModal(id) { $(id).classList.remove('open'); }
window.openModal = openModal;
window.closeModal = closeModal;

/* ═══════════════════════ Screen 2 — start gate ═══════════════════════ */
function syncReady() {
    $('btnReady').disabled = !($('lang').value && $('declare').checked);
}
$('lang').addEventListener('change', syncReady);
$('declare').addEventListener('change', syncReady);

$('btnReady').addEventListener('click', function () {
    defLang = curLang = $('lang').value;
    $('viewIn').value = defLang;
    buildPaper();
    show('scrTest');
    startClock();
    pending = null;
    render();
});

/* ═══════════════════════ Clock ═══════════════════════ */
function startClock() {
    paintClock();
    timer = setInterval(function () {
        remaining--;
        qSeconds[current] = (qSeconds[current] || 0) + 1;

        if (remaining <= 0) {
            remaining = 0;
            paintClock();
            toast('Time is up — submitting your test.');
            finish(true);
            return;
        }

        paintClock();
        $('qTime').textContent = fmtQ(qSeconds[current]);
    }, 1000);
}

function paintClock() {
    var t = Math.max(0, remaining);
    $('clkH').textContent = pad(Math.floor(t / 3600));
    $('clkM').textContent = pad(Math.floor((t % 3600) / 60));
    $('clkS').textContent = pad(t % 60);
    $('clock').classList.toggle('warn', t <= 300);
}

function fmtQ(s) { s = s || 0; return pad(Math.floor(s / 60)) + ':' + pad(s % 60); }

/* ═══════════════════════ Screen 3 — the test ═══════════════════════ */
function stateOf(i) {
    var answered = has(saved, i);
    if (marked[i] && answered) return 'marked-answered';
    if (marked[i])             return 'marked';
    if (answered)              return 'answered';
    if (visited[i])            return 'not-answered';
    return 'not-visited';
}

function buildPalette() {
    var key = Q[current].s;
    $('secName').textContent = SECTIONS[key].en;
    $('palette').innerHTML = '';

    RANGES[key].forEach(function (n) {
        var i = n - 1;
        var b = document.createElement('button');
        b.type = 'button';
        b.className = 'pal' + (i === current ? ' current' : '');
        b.dataset.state = stateOf(i);
        b.textContent = n;
        // Jumping from the palette does NOT save — same as the real CBT.
        b.addEventListener('click', function () { go(i); });
        $('palette').appendChild(b);
    });

    document.querySelectorAll('.sectab').forEach(function (t) {
        t.setAttribute('aria-selected', t.dataset.section === key ? 'true' : 'false');
    });
}

function refreshCounts() {
    var c = { answered: 0, marked: 0, 'marked-answered': 0, 'not-answered': 0, 'not-visited': 0 };
    for (var i = 0; i < TOTAL; i++) c[stateOf(i)]++;

    $('cAns').textContent     = c.answered;
    $('cMark').textContent    = c.marked;
    $('cMarkAns').textContent = c['marked-answered'];
    $('cNA').textContent      = c['not-answered'];
    $('cNV').textContent      = c['not-visited'];
}

function render() {
    var q = Q[current], body = q[curLang] || q.en;

    $('qNo').textContent = current + 1;
    $('qText').textContent = body.q;
    $('qText').className = 'qtext' + (curLang === 'hi' ? ' hi-text' : '');

    $('qOpts').querySelectorAll('span[data-opt]').forEach(function (s, i) {
        s.textContent = body.o[i];
        s.className = curLang === 'hi' ? 'hi-text' : '';
    });
    $('qOpts').querySelectorAll('input[name="choice"]').forEach(function (r) {
        r.checked = (pending !== null && Number(r.value) === pending);
        r.dataset.was = r.checked ? '1' : '0';
    });

    $('qTime').textContent = fmtQ(qSeconds[current]);
    buildPalette();
    refreshCounts();
}

/**
 * Move to another question.
 *
 * A question counts as "visited" when you LEAVE it, not when you arrive —
 * which is why the one you are sitting on stays white in the palette and the
 * Not Visited counter only drops once you move on. That matches the real CBT.
 */
function go(i) {
    if (i < 0 || i >= TOTAL || i === current) return;
    visited[current] = true;
    current = i;
    pending = has(saved, i) ? saved[i] : null;   // unsaved selections are discarded
    render();
}

function commit() {
    if (pending === null) { delete saved[current]; } else { saved[current] = pending; }
    visited[current] = true;
}

function advance() {
    if (current < TOTAL - 1) {
        go(current + 1);
    } else {
        render();
        toast('That was the last question. Use Submit Test when you are done.');
    }
}

$('btnSave').addEventListener('click', function () {
    commit();
    delete marked[current];       // saving clears a review flag
    advance();
});

$('btnMark').addEventListener('click', function () {
    commit();
    marked[current] = true;
    advance();
});

$('btnClear').addEventListener('click', function () {
    pending = null;
    delete saved[current];
    render();
});

/* Clicking the already-selected bubble deselects it (instruction 4.2).
   The browser flips .checked before this listener runs, so we remember what
   it WAS on mousedown and undo it here; `change` covers the keyboard path. */
$('qOpts').addEventListener('mousedown', function (e) {
    if (e.target.name === 'choice') e.target.dataset.was = e.target.checked ? '1' : '0';
});
$('qOpts').addEventListener('click', function (e) {
    if (e.target.name !== 'choice') return;
    if (e.target.dataset.was === '1') {
        e.target.checked = false;
        e.target.dataset.was = '0';
        pending = null;
    } else {
        pending = Number(e.target.value);
    }
});
$('qOpts').addEventListener('change', function (e) {
    if (e.target.name === 'choice') pending = e.target.checked ? Number(e.target.value) : null;
});

$('viewIn').addEventListener('change', function () { curLang = this.value; render(); });

document.querySelectorAll('.sectab').forEach(function (tab) {
    tab.addEventListener('click', function () { go(Number(this.dataset.first) - 1); });
});

$('btnReport').addEventListener('click', function () {
    toast('Reported. Our team will review this question.');
});

$('btnFs').addEventListener('click', function () {
    var d = document, e = d.documentElement;
    if (!d.fullscreenElement) {
        (e.requestFullscreen || e.webkitRequestFullscreen || function () {}).call(e);
    } else {
        (d.exitFullscreen || d.webkitExitFullscreen || function () {}).call(d);
    }
});

/* ═══════════════════════ Submit ═══════════════════════ */
$('btnSubmit').addEventListener('click', function () {
    var body = $('summaryBody');
    body.innerHTML = '';

    Object.keys(SECTIONS).forEach(function (k) {
        var nums = RANGES[k], row = { ans: 0, na: 0, mk: 0, nv: 0 };

        nums.forEach(function (n) {
            switch (stateOf(n - 1)) {
                case 'answered':        row.ans++; break;
                case 'marked-answered': row.ans++; row.mk++; break;
                case 'marked':          row.mk++;  break;
                case 'not-answered':    row.na++;  break;
                default:                row.nv++;
            }
        });

        var tr = document.createElement('tr');
        tr.innerHTML = '<td>' + SECTIONS[k].en + '</td><td>' + nums.length + '</td><td>' + row.ans +
                       '</td><td>' + row.na + '</td><td>' + row.mk + '</td><td>' + row.nv + '</td>';
        body.appendChild(tr);
    });

    openModal('submitModal');
});

$('btnSubmitConfirm').addEventListener('click', function () { finish(false); });

/* ═══════════════════════ Scoring ═══════════════════════ */
function finish(auto) {
    if (finished) return;
    finished = true;
    clearInterval(timer);
    closeModal('submitModal');

    var correct = 0, wrong = 0, bySection = {};
    Object.keys(SECTIONS).forEach(function (k) {
        bySection[k] = { total: 0, correct: 0, wrong: 0, skipped: 0, score: 0 };
    });

    Q.forEach(function (q, i) {
        var row = bySection[q.s];
        row.total++;

        if (!has(saved, i)) { row.skipped++; return; }

        if (saved[i] === KEY[i]) { correct++; row.correct++; }
        else                     { wrong++;   row.wrong++;   }
    });

    Object.keys(bySection).forEach(function (k) {
        var r = bySection[k];
        r.score = round2(r.correct - r.wrong * NEG);
    });

    var attempted = correct + wrong;
    var penalty   = round2(wrong * NEG);
    var used      = Math.min(DURATION - remaining, DURATION);

    result = {
        correct: correct, wrong: wrong, attempted: attempted,
        skipped: TOTAL - attempted, penalty: penalty,
        score: round2(correct - penalty),
        percentage: round2((correct - penalty) / TOTAL * 100),
        accuracy: attempted ? round2(correct / attempted * 100) : 0,
        speed: used ? round2(attempted / (used / 60)) : 0,
        used: used, bySection: bySection, auto: auto
    };

    paintResult();
    show('scrResult');
}

function round2(n) { return Math.round(n * 100) / 100; }
function f2(n) { return n.toFixed(2); }

function paintResult() {
    var r = result, passUR = r.percentage >= 40;

    $('autoNote').hidden = !r.auto;

    $('rScore').innerHTML = f2(r.score) + '<span style="font-size:18px;color:var(--muted)">/' + TOTAL + '</span>';
    $('rScore').style.color = passUR ? 'var(--green)' : 'var(--red)';
    $('rScoreSub').textContent = r.percentage + '% · after a ' + f2(r.penalty) + ' mark penalty';

    $('rAttempted').textContent = r.attempted;
    $('rAccuracy').textContent  = r.accuracy + '%';
    $('rSpeed').textContent = r.speed + ' Q/min · ' + Math.floor(r.used / 60) + 'm ' +
                              (r.used % 60) + 's used of ' + (DURATION / 60) + ' min';

    $('rCorrect').textContent = r.correct;
    $('rWrong').textContent   = r.wrong;
    $('rSkipped').textContent = r.skipped;

    /* section bars */
    $('rSections').innerHTML = Object.keys(SECTIONS).map(function (k) {
        var s = r.bySection[k], meta = SECTIONS[k];
        var pct = s.total ? Math.round(s.correct / s.total * 100) : 0;
        var ok  = s.score >= s.total * 0.4;
        return '<div class="secrow"><div class="secrow__top">' +
            '<span style="font-weight:700;color:' + meta.color + '">' + esc(meta.en) +
            ' <span style="color:var(--muted);font-weight:500">(' + s.total + ' Q)</span></span>' +
            '<span style="font-weight:700">' + s.correct + ' correct · ' + s.wrong + ' wrong · ' +
            s.skipped + ' skipped — <span style="color:' + (ok ? 'var(--green)' : 'var(--red)') + '">' +
            f2(s.score) + '/' + s.total + '</span></span></div>' +
            '<div class="bar"><i style="width:' + pct + '%;background:' + meta.color + '"></i></div></div>';
    }).join('');

    /* qualifying grid */
    $('rQual').innerHTML = Object.keys(QUALIFY).map(function (label) {
        var need = round2(QUALIFY[label] / 100 * TOTAL);
        var ok = r.score >= need;
        return '<div class="' + (ok ? 'yes' : 'no') + '"><b>' + esc(label) + ' (' + QUALIFY[label] + '%)</b> — ' +
            (ok ? 'Qualified' : 'Not qualified') +
            '<div style="color:var(--muted);margin-top:3px">Needs ' + need + '/' + TOTAL + '</div></div>';
    }).join('');

    /* penalty explainer */
    $('rPenalty').innerHTML = '<b>What the negative marking cost you.</b><br>' +
        r.wrong + ' wrong ' + (r.wrong === 1 ? 'answer' : 'answers') + ' × 1/3 = <b>' + f2(r.penalty) +
        '</b> marks deducted.<br>' + r.correct + ' − ' + f2(r.penalty) + ' = <b>' + f2(r.score) +
        '</b> out of ' + TOTAL + '.<br>Every 3 wrong answers wipe out 1 correct one — which is why blind ' +
        'guessing is worse than leaving a question blank.';
}

/* ═══════════════════════ Screen 5 — review ═══════════════════════ */
var rvStatus = 'all', rvSection = 'all';

function showReview() {
    buildReview();
    show('scrReview');
}
window.showReview = showReview;

function buildReview() {
    var r = result;

    $('rvBar').innerHTML =
        chipHtml('filter', 'all', 'All', r.attempted + r.skipped) +
        chipHtml('filter', 'correct', 'Correct', r.correct) +
        chipHtml('filter', 'wrong', 'Incorrect', r.wrong) +
        chipHtml('filter', 'skipped', 'Skipped', r.skipped) +
        '<span style="width:1px;height:24px;background:#EBEDF5;margin:0 4px"></span>' +
        Object.keys(SECTIONS).map(function (k) {
            return chipHtml('section', k, SECTIONS[k].short, r.bySection[k].total);
        }).join('') +
        '<span style="margin-left:auto"><a href="#" id="rvBack" style="font-weight:700;text-decoration:none">← Back to result</a></span>';

    $('rvBar').querySelector('.fchip[data-filter="all"]').classList.add('on');

    $('rvList').innerHTML = Q.map(function (q, i) {
        var mine = has(saved, i) ? saved[i] : null;
        var status = mine === null ? 'skipped' : (mine === KEY[i] ? 'correct' : 'wrong');
        var body = q[defLang] || q.en, meta = SECTIONS[q.s];

        var opts = body.o.map(function (o, oi) {
            var cls = 'rvopt' + (oi === KEY[i] ? ' key' : '') + ((mine === oi && mine !== KEY[i]) ? ' mine' : '');
            var flag = oi === KEY[i]
                ? '<span class="flag" style="color:var(--green)">✓ Correct answer</span>'
                : (mine === oi ? '<span class="flag" style="color:var(--red)">✗ Your answer</span>' : '');
            return '<div class="' + cls + '"><b>' + String.fromCharCode(65 + oi) + ')</b><span>' +
                   esc(o) + '</span>' + flag + '</div>';
        }).join('');

        return '<article class="rvcard ' + status + '" data-status="' + status + '" data-section="' + q.s + '">' +
            '<div class="rvcard__top"><div class="rvcard__q">Q' + (i + 1) + '. ' + esc(body.q) + '</div>' +
            '<span class="tag" style="background:' + meta.color + '18;color:' + meta.color + '">' +
            esc(meta.short) + '</span></div>' + opts +
            (mine === null ? '<div style="font-size:13px;color:var(--muted);margin-top:8px">Not attempted — no marks awarded, no penalty applied.</div>' : '') +
            '</article>';
    }).join('');

    wireReview();
    applyReview();
}

function chipHtml(kind, value, label, n) {
    return '<button type="button" class="fchip" data-' + kind + '="' + value + '">' +
           esc(label) + ' <span class="n">' + n + '</span></button>';
}

function wireReview() {
    $('rvBack').addEventListener('click', function (e) { e.preventDefault(); show('scrResult'); });

    $('rvBar').querySelectorAll('.fchip[data-filter]').forEach(function (b) {
        b.addEventListener('click', function () {
            $('rvBar').querySelectorAll('.fchip[data-filter]').forEach(function (x) { x.classList.remove('on'); });
            b.classList.add('on');
            rvStatus = b.dataset.filter;
            applyReview();
        });
    });

    $('rvBar').querySelectorAll('.fchip[data-section]').forEach(function (b) {
        b.addEventListener('click', function () {
            var already = b.classList.contains('on');
            $('rvBar').querySelectorAll('.fchip[data-section]').forEach(function (x) { x.classList.remove('on'); });
            // Clicking the active section chip again clears the section filter.
            if (already) { rvSection = 'all'; } else { b.classList.add('on'); rvSection = b.dataset.section; }
            applyReview();
        });
    });
}

function applyReview() {
    var shown = 0;
    $('rvList').querySelectorAll('.rvcard').forEach(function (c) {
        var ok = (rvStatus === 'all' || c.dataset.status === rvStatus) &&
                 (rvSection === 'all' || c.dataset.section === rvSection);
        c.hidden = !ok;
        if (ok) shown++;
    });
    $('rvEmpty').hidden = shown > 0;
}

/* ═══════════════════════ Question paper modal ═══════════════════════ */
function buildPaper() {
    $('paperBody').innerHTML = Q.map(function (q, i) {
        var b = q[defLang] || q.en;
        return '<div style="margin-bottom:18px;padding-bottom:14px;border-bottom:1px solid #EEF1F3">' +
            '<div style="font-weight:700;margin-bottom:6px">Q' + (i + 1) + '. ' + esc(b.q) + '</div>' +
            b.o.map(function (o, oi) {
                return '<div style="margin-left:14px">' + String.fromCharCode(65 + oi) + ') ' + esc(o) + '</div>';
            }).join('') + '</div>';
    }).join('');
}

/* Warn before leaving mid-test — but not on the instruction or result screens. */
window.addEventListener('beforeunload', function (e) {
    if ($('scrTest').hidden || finished) return;
    e.preventDefault();
    e.returnValue = '';
});

/* ═══════════════════════ Boot ═══════════════════════ */
syncReady();
show('scrGeneral');
})();
</script>

</body>
</html>
