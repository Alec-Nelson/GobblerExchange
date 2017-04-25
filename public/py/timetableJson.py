from pyvt import Timetable
import json

#Note: This code requires the installation of pyvt, using pip install py-vt.
#This produces a json file with all courses offered this semester
#It is not set up for use directly in the website, only for supplemental use by admins at the start of each semester

timetable = Timetable()

courseCodes = ["ACIS","AFST","AHRM","AINS","ALCE","ALS","AOE","APS","APSC","ARBC","ARCH","ART","AS","ASPT","AT","BC","BCHM","BIOL","BIT","BMES","BMSP","BMVS","BSE","BTDM","CEE","CEM","CHE","CHEM","CHN","CINE","CLA","CMDA","CNST","COMM","COS","CRIM","CS","CSES","DASC","ECE","ECON","EDCI","EDCO","EDCT","EDEL","EDEP","EDHE","EDIT","EDP","EDRE","EDTE","ENGE","ENGL","ENGR","ENSC","ENT","ESM","FA","FIN","FIW","FL","FR","FREC","FST","GBCB","GEOG","GEOS","GER","GIA","GR","GRAD","HD","HEB","HIST","HNFE","HORT","HTM","HUM","IDS","IS","ISC","ISE","ITAL","ITDS","JPN","JUD","LAHS","LAR","LAT","LDRS","MACR","MATH","ME","MGT","MINE","MKTG","MN","MS","MSE","MTRG","MUS","NANO","NEUR","NR","NSEG","PAPA","PHIL","PHS","PHYS","PORT","PPWS","PSCI","PSVP","PSYC","REAL","RLCL","RUS","SBIO","SOC","SPAN","SPIA","STAT","STL","STS","SYSB","TA","TBMH","UAP","UH","UNIV","VM","WGS"]
#Note: C21S, CONS, FCS, FMD, PM, RED, RTM have no sections
sectionList = []
for code in courseCodes:
        #print(str(code))
        sections = timetable.subject_lookup(code, open_only=False)
        for section in sections:
            sectionDict = {}
            sectionDict["crn"] = section.crn
            sectionDict["code"] = section.code
            sectionDict["name"] = section.name
            sectionDict["instructor"] = section.instructor
            sectionList.append(sectionDict)
with open("timetable.json", 'w') as outfile:
        json.dump(sectionList, outfile)
        outfile.close
#print(str(sectionList))
#print(str(sectionList[1]["name"]))
'''
[{'crn':'1223', 'name':'blah'},{crn:'122222', 'name':'bleh'}]
'''
