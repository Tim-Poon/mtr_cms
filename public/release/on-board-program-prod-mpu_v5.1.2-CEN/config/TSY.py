# on-board-program-from-pi version of different sites
from collections import namedtuple
from enum import Enum
from config.common import SOURCE_INFO
import os

MAPS = {
11:[[12688100.201971333,2486150.063684519,1],[12688101.011343628,2486152.857219047,1],[12688142.61480957,2486142.073515705,1],[12688141.75359984,2486139.103230061,1]],
12:[[12688173.185013555,2486149.0631858804,1],[12688135.558093194,2486159.0908580683,1],[12688133.436789503,2486151.894726257,1],[12688170.819013288,2486141.2638497604,1]],
13:[[12688133.152246946,2486152.1441932702,1],[12688131.144050933,2486145.043645988,1],[12688139.262685997,2486143.0998480874,1],[12688141.133149328,2486149.894309872,1]],
14:[[12688140.285845382,2486147.677580333,1],[12688142.774114465,2486147.007432178,1],[12688141.43419165,2486142.4493170506,1],[12688139.035949273,2486143.0971562197,1]],
15:[[12688142.618298294,2486141.9998934767,1],[12688143.0714567,2486143.2975338926,1],[12688141.725880485,2486143.6488222107,1],[12688141.468541998,2486142.427587605,1]],
16:[[12688142.769200489,2486142.726795942,1],[12688143.713834558,2486142.457015911,1],[12688143.12171175,2486140.479920123,1],[12688142.077401862,2486140.7387057957,1]],
17:[[12688124.42033689,2486143.7352232346,1],[12688123.77126617,2486141.3617521697,1],[12688127.857186183,2486140.277849779,1],[12688128.538735712,2486142.69681452,1]],
18:[[12688130.54984733,2486142.220644468,1],[12688128.205605663,2486133.9611421474,1],[12688146.8054418,2486128.942285613,1],[12688147.595490705,2486131.6691288785,1]],
19:[[12688128.484433886,2486142.8031067667,1],[12688145.35242726,2486138.3141882983,1],[12688145.12975374,2486137.645667077,1],[12688128.18274519,2486142.2740877783,1]],
110:[[12688132.408403601,2486141.25375048,1],[12688131.892021619,2486139.37143272,1],[12688137.770550262,2486137.7538787723,1],[12688138.369997043,2486139.722827738,1]],
111:[[12688140.679838993,2486140.7651602803,1],[12688139.361281512,2486136.1354654036,1],[12688143.072004104,2486135.0893804184,1],[12688144.442992706,2486139.663900434,1]],
112:[[12688145.65536196,2486139.3634689106,1],[12688144.405660301,2486139.6942429896,1],[12688143.778503224,2486137.5296199154,1],[12688145.101205055,2486137.686945602,1]],
113:[[12688145.414190533,2486156.087641881,1],[12688161.56789192,2486208.882717778,1],[12688167.935433874,2486207.45750892,1],[12688152.624291996,2486154.4644664684,1]],
114:[[12688148.472365431,2486133.9738435005,1],[12688141.293888314,2486136.007694992,1],[12688140.61864135,2486133.4334805263,1],[12688147.637600742,2486131.359093001,1]],
115:[[12688128.869942429,2486140.0842040656,1],[12688127.344702441,2486134.2870044378,1],[12688128.464565037,2486133.955954093,1],[12688130.003678348,2486140.267287441,1]],
116:[[12688143.290898668,2486149.152221475,1],[12688141.709403634,2486143.493896461,1],[12688145.700472383,2486142.415651621,1],[12688147.214770924,2486148.1084760036,1]],
117:[[12688156.235236274,2486206.824625191,1],[12688153.990926012,2486199.2055807477,1],[12688158.403142085,2486197.9078338784,1],[12688160.977621885,2486205.6494412525,1]],
118:[[12688145.386126645,2486166.754608788,1],[12688143.188299045,2486159.599398383,1],[12688146.565396374,2486158.8004674492,1],[12688148.59436806,2486165.8821568186,1]],
119:[[12688148.348100986,2486176.8625591598,1],[12688146.209124329,2486169.2109959964,1],[12688149.41469629,2486168.4424027544,1],[12688152.025932578,2486175.9425867824,1]],
120:[[12688134.480803696,2486156.07480709,1],[12688142.417075353,2486182.0128711434,1],[12688149.55233416,2486180.251157855,1],[12688142.554937255,2486156.0490334663,1]],
121:[[12688145.746645855,2486198.9232307333,1],[12688157.59418183,2486195.6530905245,1],[12688155.483947847,2486188.120173909,1],[12688143.623182273,2486191.594154565,1]],
122:[[12688146.665852508,2486198.2107059555,1],[12688150.356960103,2486211.072872606,1],[12688157.288678043,2486209.289558875,1],[12688153.156123342,2486196.372773422,1]],
123:[[12688128.58267611,2486191.029877724,1],[12688127.01177717,2486185.772595847,1],[12688152.829089869,2486178.404440072,1],[12688155.174254788,2486185.675544847,1]],
124:[[12688142.5006727,2486187.5368196233,1],[12688145.170643864,2486191.5986925336,1],[12688151.374234822,2486189.7967316112,1],[12688150.175309096,2486186.4966535517,1]],
125:[[12688124.313534597,2486181.306418137,1],[12688123.908280894,2486179.802662185,1],[12688141.727241168,2486173.3147136774,1],[12688142.832167141,2486175.9012498874,1]],
126:[[12688131.88456146,2486184.475354433,1],[12688130.373228706,2486179.1779236756,1],[12688138.101842094,2486176.9802251616,1],[12688139.329735367,2486182.43482065,1]],
127:[[12688138.570344651,2486179.7326990906,1],[12688141.875010502,2486178.892055389,1],[12688140.876028791,2486176.336231388,1],[12688138.232234402,2486177.186960879,1]],
128:[[12688126.23609459,2486182.98476962,1],[12688125.588320294,2486180.6678894805,1],[12688130.322155891,2486179.505891282,1],[12688131.075250294,2486181.861867598,1]],
129:[[12688127.024629239,2486185.9657526715,1],[12688126.101383159,2486182.8697635294,1],[12688128.801119016,2486182.2250245186,1],[12688129.524981964,2486185.1762745213,1]],
130:[[12688163.820023548,2486193.902714132,1],[12688165.032318978,2486193.4711262356,1],[12688162.819217866,2486186.007547705,1],[12688161.527657822,2486186.345449628,1]],
131:[[12688175.022875652,2486202.694605983,1],[12688171.627365632,2486205.2755984464,1],[12688165.399035329,2486201.2403844227,1],[12688166.387550237,2486197.936180961,1]],
132:[[12688166.43931343,2486203.9983810233,1],[12688168.391842889,2486203.1340694013,1],[12688165.851177232,2486197.6605034387,1],[12688164.223052388,2486197.846200212,1]],
133:[[12688166.4726822,2486198.010499904,1],[12688164.250656521,2486195.252287688,1],[12688163.849223075,2486196.436416074,1],[12688166.108858379,2486199.2541125203,1]],
134:[[12688132.753639821,2486189.9656144967,1],[12688143.732973605,2486189.0860749576,1],[12688143.409713501,2486188.166676939,1],[12688139.463882297,2486188.486904379,1]],
135:[[12688116.496753838,2486148.5010981383,1],[12688132.248678558,2486145.8930314234,1],[12688132.065261161,2486145.2599695274,1],[12688125.349996002,2486146.3689006157,1]],
136:[[12688100.924253007,2486150.191418076,1],[12688100.831754085,2486149.460382756,1],[12688108.406940937,2486147.399319378,1],[12688108.634275429,2486148.115330945,1]],
}

SOURCE_INFO_DICT = {
'1006120011': SOURCE_INFO(source_identifier='1006120011', x=12688157.11212, y=2486155.7998476, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110004': SOURCE_INFO(source_identifier='1006110004', x=12688106.072173, y=2486147.8629962, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110005': SOURCE_INFO(source_identifier='1006110005', x=12688108.64082, y=2486147.1372355, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110006': SOURCE_INFO(source_identifier='1006110006', x=12688113.303281, y=2486149.9082536, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110008': SOURCE_INFO(source_identifier='1006110008', x=12688120.910962, y=2486148.147171, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110009': SOURCE_INFO(source_identifier='1006110009', x=12688127.120199, y=2486146.978299, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110010': SOURCE_INFO(source_identifier='1006110010', x=12688131.324616, y=2486146.2611722, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1006110011': SOURCE_INFO(source_identifier='1006110011', x=12688129.917476, y=2486140.7550604, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110012': SOURCE_INFO(source_identifier='1006110012', x=12688132.666104, y=2486132.8521012, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110013': SOURCE_INFO(source_identifier='1006110013', x=12688137.304235, y=2486131.4432211, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110014': SOURCE_INFO(source_identifier='1006110014', x=12688141.870748, y=2486129.8965683, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1006110015': SOURCE_INFO(source_identifier='1006110015', x=12688138.151487, y=2486138.4646245, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1006110017': SOURCE_INFO(source_identifier='1006110017', x=12688139.760405, y=2486138.0252859, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1006110018': SOURCE_INFO(source_identifier='1006110018', x=12688139.209535, y=2486139.1068442, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110019': SOURCE_INFO(source_identifier='1006110019', x=12688132.739022, y=2486150.8589293, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110023': SOURCE_INFO(source_identifier='1006110023', x=12688141.980333, y=2486149.3184691, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1006110021': SOURCE_INFO(source_identifier='1006110021', x=12688141.683705, y=2486147.4287745, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1006110020': SOURCE_INFO(source_identifier='1006110020', x=12688140.950403, y=2486148.6277852, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1006110022': SOURCE_INFO(source_identifier='1006110022', x=12688142.686326, y=2486148.3582398, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110001': SOURCE_INFO(source_identifier='1006110001', x=12688100.406538, y=2486149.4705494, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110002': SOURCE_INFO(source_identifier='1006110002', x=12688103.011194, y=2486148.735865, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1006110026': SOURCE_INFO(source_identifier='1006110026', x=12688143.675657, y=2486158.2452519, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1006110027': SOURCE_INFO(source_identifier='1006110027', x=12688144.290332, y=2486157.2362635, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110030': SOURCE_INFO(source_identifier='1006110030', x=12688136.522736, y=2486163.7300127, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110031': SOURCE_INFO(source_identifier='1006110031', x=12688137.581027, y=2486166.9891168, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110032': SOURCE_INFO(source_identifier='1006110032', x=12688138.275498, y=2486170.1187622, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110037': SOURCE_INFO(source_identifier='1006110037', x=12688139.195025, y=2486173.2682022, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110039': SOURCE_INFO(source_identifier='1006110039', x=12688137.317717, y=2486173.9445783, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110040': SOURCE_INFO(source_identifier='1006110040', x=12688133.61261, y=2486176.2264608, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110041': SOURCE_INFO(source_identifier='1006110041', x=12688128.851517, y=2486177.9637411, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110042': SOURCE_INFO(source_identifier='1006110042', x=12688123.931733, y=2486179.629712, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110043': SOURCE_INFO(source_identifier='1006110043', x=12688130.634792, y=2486184.3460664, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110045': SOURCE_INFO(source_identifier='1006110045', x=12688132.407142, y=2486190.6260662, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110046': SOURCE_INFO(source_identifier='1006110046', x=12688137.563756, y=2486190.1980481, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110036': SOURCE_INFO(source_identifier='1006110036', x=12688147.456015, y=2486168.5440007, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110034': SOURCE_INFO(source_identifier='1006110034', x=12688146.933673, y=2486166.6745467, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110047': SOURCE_INFO(source_identifier='1006110047', x=12688142.674307, y=2486189.2791675, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1006110048': SOURCE_INFO(source_identifier='1006110048', x=12688140.505779, y=2486181.6221355, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1006110038': SOURCE_INFO(source_identifier='1006110038', x=12688139.900448, y=2486179.4618646, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110053': SOURCE_INFO(source_identifier='1006110053', x=12688144.04867, y=2486190.1192268, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1006110049': SOURCE_INFO(source_identifier='1006110049', x=12688149.024805, y=2486178.0709094, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1006110051': SOURCE_INFO(source_identifier='1006110051', x=12688151.154765, y=2486177.4655667, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1006110050': SOURCE_INFO(source_identifier='1006110050', x=12688149.774015, y=2486176.6060327, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1006110055': SOURCE_INFO(source_identifier='1006110055', x=12688152.54609, y=2486186.6011795, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1006110054': SOURCE_INFO(source_identifier='1006110054', x=12688151.475583, y=2486187.9684643, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110057': SOURCE_INFO(source_identifier='1006110057', x=12688153.102704, y=2486188.5882377, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1006110056': SOURCE_INFO(source_identifier='1006110056', x=12688154.187367, y=2486187.1778084, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110058': SOURCE_INFO(source_identifier='1006110058', x=12688144.443851, y=2486195.5516708, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110059': SOURCE_INFO(source_identifier='1006110059', x=12688145.417113, y=2486199.1334215, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110061': SOURCE_INFO(source_identifier='1006110061', x=12688155.310958, y=2486196.4500141, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1006110062': SOURCE_INFO(source_identifier='1006110062', x=12688157.585588, y=2486197.7067674, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1006110063': SOURCE_INFO(source_identifier='1006110063', x=12688159.717271, y=2486206.9419978, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110064': SOURCE_INFO(source_identifier='1006110064', x=12688174.440869, y=2486202.463637, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110065': SOURCE_INFO(source_identifier='1006110065', x=12688170.815054, y=2486200.0068536, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110066': SOURCE_INFO(source_identifier='1006110066', x=12688166.765812, y=2486198.1266355, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110067': SOURCE_INFO(source_identifier='1006110067', x=12688164.327327, y=2486194.9795464, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110069': SOURCE_INFO(source_identifier='1006110069', x=12688161.639487, y=2486185.0572382, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110070': SOURCE_INFO(source_identifier='1006110070', x=12688165.475979, y=2486181.4622514, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110071': SOURCE_INFO(source_identifier='1006110071', x=12688158.813253, y=2486175.2840506, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006120006': SOURCE_INFO(source_identifier='1006120006', x=12688130.527656, y=2486194.647338, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006120007': SOURCE_INFO(source_identifier='1006120007', x=12688137.050161, y=2486193.7806149, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006120008': SOURCE_INFO(source_identifier='1006120008', x=12688141.936625, y=2486193.0583388, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006120009': SOURCE_INFO(source_identifier='1006120009', x=12688138.672395, y=2486199.7210083, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006120018': SOURCE_INFO(source_identifier='1006120018', x=12688169.833265, y=2486196.0626404, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006120017': SOURCE_INFO(source_identifier='1006120017', x=12688168.525936, y=2486190.8361353, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006120010': SOURCE_INFO(source_identifier='1006120010', x=12688159.057803, y=2486162.6768922, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006120012': SOURCE_INFO(source_identifier='1006120012', x=12688164.436444, y=2486159.915488, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006120016': SOURCE_INFO(source_identifier='1006120016', x=12688169.578133, y=2486158.5980617, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006120015': SOURCE_INFO(source_identifier='1006120015', x=12688163.283935, y=2486140.0720933, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006120013': SOURCE_INFO(source_identifier='1006120013', x=12688153.409986, y=2486143.2077562, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110072': SOURCE_INFO(source_identifier='1006110072', x=12688157.630886, y=2486171.0775824, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110073': SOURCE_INFO(source_identifier='1006110073', x=12688156.459943, y=2486166.3555717, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110074': SOURCE_INFO(source_identifier='1006110074', x=12688154.740019, y=2486160.2283178, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110075': SOURCE_INFO(source_identifier='1006110075', x=12688153.446702, y=2486155.4980318, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110077': SOURCE_INFO(source_identifier='1006110077', x=12688158.94225, y=2486152.9442337, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110078': SOURCE_INFO(source_identifier='1006110078', x=12688159.123292, y=2486152.9053162, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110079': SOURCE_INFO(source_identifier='1006110079', x=12688163.918485, y=2486151.5385701, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110080': SOURCE_INFO(source_identifier='1006110080', x=12688164.057703, y=2486151.4862237, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110081': SOURCE_INFO(source_identifier='1006110081', x=12688168.859012, y=2486150.2071976, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110082': SOURCE_INFO(source_identifier='1006110082', x=12688169.025884, y=2486150.183693, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110083': SOURCE_INFO(source_identifier='1006110083', x=12688172.403187, y=2486149.3198056, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110084': SOURCE_INFO(source_identifier='1006110084', x=12688165.882678, y=2486142.1479558, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110086': SOURCE_INFO(source_identifier='1006110086', x=12688161.732839, y=2486143.9437414, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110087': SOURCE_INFO(source_identifier='1006110087', x=12688157.009669, y=2486145.2926414, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110088': SOURCE_INFO(source_identifier='1006110088', x=12688156.83444, y=2486145.3539113, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110089': SOURCE_INFO(source_identifier='1006110089', x=12688152.088434, y=2486146.6906225, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110090': SOURCE_INFO(source_identifier='1006110090', x=12688151.908381, y=2486146.7352408, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110091': SOURCE_INFO(source_identifier='1006110091', x=12688147.46204, y=2486147.8629064, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110092': SOURCE_INFO(source_identifier='1006110092', x=12688146.484577, y=2486144.8237907, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006120003': SOURCE_INFO(source_identifier='1006120003', x=12688139.522043, y=2486128.6074992, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006120001': SOURCE_INFO(source_identifier='1006120001', x=12688116.942718, y=2486152.4406702, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006120002': SOURCE_INFO(source_identifier='1006120002', x=12688130.25703, y=2486149.0371768, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006120014': SOURCE_INFO(source_identifier='1006120014', x=12688128.426496, y=2486154.7670399, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110003': SOURCE_INFO(source_identifier='1006110003', x=12688105.947799, y=2486151.9629956, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110007': SOURCE_INFO(source_identifier='1006110007', x=12688116.544313, y=2486145.5705577, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1006110016': SOURCE_INFO(source_identifier='1006110016', x=12688138.751678, y=2486137.4087257, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110029': SOURCE_INFO(source_identifier='1006110029', x=12688144.90623, y=2486159.0289928, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1006110028': SOURCE_INFO(source_identifier='1006110028', x=12688145.306733, y=2486157.6804373, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110044': SOURCE_INFO(source_identifier='1006110044', x=12688124.252615, y=2486192.2541569, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110052': SOURCE_INFO(source_identifier='1006110052', x=12688150.413225, y=2486178.8122497, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1006110060': SOURCE_INFO(source_identifier='1006110060', x=12688153.65182, y=2486197.1973498, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110068': SOURCE_INFO(source_identifier='1006110068', x=12688163.532816, y=2486187.7957628, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110076': SOURCE_INFO(source_identifier='1006110076', x=12688153.825548, y=2486154.5334302, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110085': SOURCE_INFO(source_identifier='1006110085', x=12688161.926074, y=2486143.8780097, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006120019': SOURCE_INFO(source_identifier='1006120019', x=12688127.881909, y=2486132.0294166, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110033': SOURCE_INFO(source_identifier='1006110033', x=12688146.36548, y=2486167.7825124, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110035': SOURCE_INFO(source_identifier='1006110035', x=12688148.035625, y=2486167.3473824, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006120004': SOURCE_INFO(source_identifier='1006120004', x=12688130.35035, y=2486159.6776143, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006120005': SOURCE_INFO(source_identifier='1006120005', x=12688129.543023, y=2486175.6115756, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110024': SOURCE_INFO(source_identifier='1006110024', x=12688134.183561, y=2486155.8630272, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1006110025': SOURCE_INFO(source_identifier='1006110025', x=12688135.136219, y=2486160.5754458, z=1, type='non-lon-lat', activated=True, addition_info={}),
}

# [data_pool]
# maximum time range inside a current data pool
window_size_in_sec = 2.
# time gap between two updates in data pool(second)
sec_per_update = 1

# [TRACKING]
CAL_PERIOD_SEC = 1.5  # time gap between two calculations (second)

ALARM_PARAMS = {
     'ALARM_PIN': 17,

     # rule 1: alarm |= within(pos, ALARM_ZONE) and recent(vel_history) > ALARM_VELOCITY_THRESHOLD_HIGHER
     'ALARM_VELOCITY_THRESHOLD_HIGHER': 1.6,
     'ALARM_TOLERANCE_NUMBER': 5,

     # rule 2: alarm |= within(pos, ALARM_ZONE) and step > STEP_ALARM_THRESHOLD_HIGHER
     'STEP_ALARM_THRESHOLD_HIGHER': 12,

     # rule 3: alarm |= within(pos, ALARM_ZONE) and step > STEP_ALARM_THRESHOLD_LOWER and abs(vel) >ALARM_VELOCITY_THRESHOLD_LOWER
     'ALARM_VELOCITY_THRESHOLD_LOWER': 1.42,
     'STEP_ALARM_THRESHOLD_LOWER': 10,

     'ALARM_ZONE': [polygon for polyid, polygon in MAPS.items()],  # it can't be empty list or None.
}

FILTER_MODULE_PARAMS = {
    'RSSI_FILTER_ALGO_FILE': "avg_filter.py",
    'RSSI_THRESHOLD': -80,
}

LOCALIZATION_MODULE_PARAMS = {
    'LOCALIZATION_ALGO_FILE': "new_localization_pf.cpython-37m-arm-linux-gnueabihf.so",
    'num_of_particles': 600,
    'max_vel': 2.,  # maximum speed of human, unit in m/s
    'obs_model_mean': [-75.0656, 2.8871],  # mean and variance of P(rssi, distance)
    'obs_model_var': [[53.171, -5.218], [-5.218, 2.912]],
    'loss_model_range': [-120, -110],
    'weight_observe': 0.8,  # mean and variance of P(rssi, distance)
    'weight_loss': 1 - 0.8,
    'grid_size': 0.5,
    'cal_fq': CAL_PERIOD_SEC,
}

VELOCITY_MODULE_PARAMS = {
    'VELOCITY_ALGO_FILE': "analyse_velocity.py",
    'VELOCITY_LEAST_SQUARE_WINDOW': 8,  # maximum num of position used to calculate velocity via least square
    'LOCALIZATION_CAL_PERIOD': CAL_PERIOD_SEC,
}
