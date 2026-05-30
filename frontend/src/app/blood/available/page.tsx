'use client';

import { useQuery } from '@tanstack/react-query';
import { apiClient } from '@/services/apiClient';
import { useAuthStore } from '@/store/authStore';
import { isCompatible } from '@/lib/bloodCompatibility';
import { useState } from 'react';
import { Loader2, Heart, MapPin, Calendar } from 'lucide-react';

interface BloodSample {
  id: number;
  units_available: number;
  expiry_date: string;
  group_name: string;
  hospital_name: string;
  address: string;
}

export default function AvailableBloodPage() {
  const { user, isAuthenticated } = useAuthStore();
  const [requestingId, setRequestingId] = useState<number | null>(null);
  const [unitsRequested, setUnitsRequested] = useState<Record<number, number>>({});
  const [message, setMessage] = useState<{ text: string; type: 'success' | 'error' } | null>(null);

  const { data, isLoading, refetch } = useQuery({
    queryKey: ['available-blood'],
    queryFn: async () => {
      const response = await apiClient.get('/blood-samples');
      return response.data.data as BloodSample[];
    }
  });

  const handleRequest = async (sample: BloodSample) => {
    if (!isAuthenticated) {
      window.location.href = '/login';
      return;
    }
    
    // In a real app, user blood group would be stored in auth state
    // But we check on the backend anyway. Here we just blindly fire the request and let the backend decide if it's compatible.
    
    try {
      setRequestingId(sample.id);
      setMessage(null);
      const requested = unitsRequested[sample.id] || 1;
      await apiClient.post('/requests', { blood_sample_id: sample.id, units_requested: requested });
      setMessage({ text: `Successfully requested ${requested} unit(s)!`, type: 'success' });
      // We don't necessarily refetch immediately unless we want to hide it
    } catch (err: any) {
      setMessage({ text: err.response?.data?.message || 'Failed to request blood.', type: 'error' });
    } finally {
      setRequestingId(null);
    }
  };

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div className="mb-8">
        <h1 className="text-3xl font-bold text-gray-900">Available Blood Samples</h1>
        <p className="mt-2 text-sm text-gray-500">Find and request life-saving blood from verified hospitals.</p>
      </div>

      {message && (
        <div className={`mb-6 p-4 rounded-md ${message.type === 'success' ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800'}`}>
          {message.text}
        </div>
      )}

      {isLoading ? (
        <div className="flex justify-center py-12">
          <Loader2 className="h-8 w-8 animate-spin text-red-600" />
        </div>
      ) : data?.length === 0 ? (
        <div className="text-center py-12 bg-white rounded-lg border border-gray-200">
          <Heart className="mx-auto h-12 w-12 text-gray-300 mb-3" />
          <h3 className="text-lg font-medium text-gray-900">No blood samples available</h3>
          <p className="text-gray-500 mt-1">Check back later or contact local hospitals.</p>
        </div>
      ) : (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {data?.map((sample) => (
            <div key={sample.id} className="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col transition-shadow hover:shadow-md">
              <div className="flex items-center justify-between mb-4">
                <div className="flex items-center gap-3">
                  <div className="h-12 w-12 rounded-full bg-red-100 flex items-center justify-center">
                    <span className="text-red-700 font-bold text-lg">{sample.group_name}</span>
                  </div>
                  <div>
                    <h3 className="text-lg font-semibold text-gray-900">{sample.hospital_name}</h3>
                    <p className="text-sm text-gray-500">{sample.units_available} Units Available</p>
                  </div>
                </div>
              </div>
              
              <div className="mt-2 flex-1 space-y-2">
                <div className="flex items-start gap-2 text-sm text-gray-600">
                  <MapPin className="h-4 w-4 mt-0.5 text-gray-400 shrink-0" />
                  <span>{sample.address}</span>
                </div>
                <div className="flex items-center gap-2 text-sm text-gray-600">
                  <Calendar className="h-4 w-4 text-gray-400 shrink-0" />
                  <span>Expires: {new Date(sample.expiry_date).toLocaleDateString()}</span>
                </div>
              </div>

              <div className="mt-6 pt-4 border-t border-gray-100 space-y-3">
                <div className="flex items-center justify-between">
                  <label htmlFor={`units-${sample.id}`} className="text-sm font-medium text-gray-700">
                    Units Needed:
                  </label>
                  <input
                    type="number"
                    id={`units-${sample.id}`}
                    min="1"
                    max={sample.units_available}
                    value={unitsRequested[sample.id] || 1}
                    onChange={(e) => {
                      const val = parseInt(e.target.value);
                      if (!isNaN(val) && val >= 1 && val <= sample.units_available) {
                        setUnitsRequested({ ...unitsRequested, [sample.id]: val });
                      }
                    }}
                    className="w-20 px-2 py-1 text-sm border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500"
                  />
                </div>
                <button
                  onClick={() => handleRequest(sample)}
                  disabled={requestingId === sample.id || (user?.role === 'hospital') || sample.units_available < 1}
                  className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  {requestingId === sample.id ? (
                    <Loader2 className="animate-spin h-5 w-5" />
                  ) : user?.role === 'hospital' ? (
                    'Hospitals Cannot Request'
                  ) : sample.units_available < 1 ? (
                    'Out of Stock'
                  ) : (
                    'Request Sample'
                  )}
                </button>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}
