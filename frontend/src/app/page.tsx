import Link from 'next/link';
import { Activity, ShieldCheck, HeartPulse } from 'lucide-react';

export default function Home() {
  return (
    <div className="flex flex-col min-h-[calc(100vh-4rem)]">
      {/* Hero Section */}
      <section className="flex-1 flex items-center justify-center bg-gradient-to-b from-red-50 to-white pt-20 pb-32">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-100 text-red-700 font-medium text-sm mb-8 animate-fade-in-up">
            <HeartPulse className="h-4 w-4" />
            Save Lives, Donate Blood
          </div>
          <h1 className="text-5xl md:text-6xl font-extrabold text-gray-900 tracking-tight mb-6">
            Connecting <span className="text-red-600">Donors</span> with <span className="text-red-600">Hospitals</span>
          </h1>
          <p className="max-w-2xl mx-auto text-xl text-gray-500 mb-10">
            A modern, efficient platform ensuring that life-saving blood is always available where it's needed most. Register today to make a difference.
          </p>
          <div className="flex flex-col sm:flex-row justify-center gap-4">
            <Link href="/blood/available" className="inline-flex items-center justify-center px-8 py-3.5 text-base font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-sm transition-all hover:shadow-md">
              Find Available Blood
            </Link>
            <Link href="/register-hospital" className="inline-flex items-center justify-center px-8 py-3.5 text-base font-medium text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg transition-all">
              Register Hospital
            </Link>
          </div>
        </div>
      </section>

      {/* Features Section */}
      <section className="py-24 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div className="text-center">
              <div className="mx-auto h-16 w-16 flex items-center justify-center rounded-2xl bg-red-50 text-red-600 mb-6">
                <Activity className="h-8 w-8" />
              </div>
              <h3 className="text-xl font-bold text-gray-900 mb-3">Real-time Inventory</h3>
              <p className="text-gray-500">Hospitals can update and track their blood inventory instantly, ensuring accurate availability.</p>
            </div>
            <div className="text-center">
              <div className="mx-auto h-16 w-16 flex items-center justify-center rounded-2xl bg-red-50 text-red-600 mb-6">
                <ShieldCheck className="h-8 w-8" />
              </div>
              <h3 className="text-xl font-bold text-gray-900 mb-3">Verified Hospitals</h3>
              <p className="text-gray-500">All registered hospitals go through a verification process to ensure safe and legitimate transactions.</p>
            </div>
            <div className="text-center">
              <div className="mx-auto h-16 w-16 flex items-center justify-center rounded-2xl bg-red-50 text-red-600 mb-6">
                <HeartPulse className="h-8 w-8" />
              </div>
              <h3 className="text-xl font-bold text-gray-900 mb-3">Instant Requests</h3>
              <p className="text-gray-500">Receivers can find compatible blood and send immediate requests to hospitals nearby.</p>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}
