#include<iostream>
#include<string>

using namespace std;

int main() {

	string st1 = "Ahmedabad med med med";

	int index = st1.find_first_of(string("ama"));
	cout << index << endl;
	index = st1.find_last_of(string("med"));
	cout << index << endl;

	index = st1.find_first_of('m');
	cout << index << endl;
	index = st1.find_last_of('m');
	cout << index << endl;
	
	return 0;
}
